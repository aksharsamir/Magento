<?php

namespace Etailors\Forms\Block\Form\Field;

use Magento\Framework\Registry;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Helper\Session as SessionHelper;
use Magento\Customer\Model\Session;
use Etailors\Forms\Helper\Processor;
use Etailors\Forms\Model\Config\Data\Autofill as AutofillConfig;
use Etailors\Forms\Block\Form\Field\AutofillFactory;
use Etailors\Forms\Helper\Configuration as ConfigurationHelper;

class Renderer extends \Etailors\Forms\Block\Form\Field
{

    /**
     * @var Registry
     */
    protected $registry;
    
    /**
     * @var AutofillConfig
     */
    protected $autofillConfig;
    
    /**
     * @var AutofillFactory
     */
    protected $autofillFactory;
    
    /**
     * @var ConfigurationHelper
     */
    protected $configurationHelper;
    
    /**
     * @param TemplateContext     $context
     * @param Registry            $registry
     * @param SessionHelper       $sessionHelper
     * @param Processor           $processor
     * @param Session             $session
     * @param AutofillConfig      $autofillConfig
     * @param AutofillFactory     $autofillFactory
     * @param ConfigurationHelper $configurationHelper
     * @param array               $data
     * @return void
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        SessionHelper $sessionHelper,
        Processor $processor,
        Session $session,
        AutofillConfig $autofillConfig,
        AutofillFactory $autofillFactory,
        ConfigurationHelper $configurationHelper,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->autofillConfig = $autofillConfig;
        $this->autofillFactory = $autofillFactory;
        $this->configurationHelper = $configurationHelper;
        parent::__construct($context, $sessionHelper, $processor, $session, $data);
    }
    
    /**
     * Set the template from form
     * @return void
     */
    public function _prepareLayout()
    {
        parent::_prepareLayout();
        $fieldType = $this->getData('field')->getType();
        $fieldTemplate = 'Etailors_Forms::field/renderer/type/' . $fieldType . '.phtml';
        $this->setTemplate($fieldTemplate);
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return explode("\n", $this->getData('field')->getOptions());
    }
    
    /**
     * @return string|array
     */
    public function getPredefinedValue()
    {
        $field = $this->getField();
        $options = $field->getOptions();
        
        if ($this->autofillConfig->hasOption($options)) {
            $optionDetails = $this->autofillConfig->getOptionDetails($options);
            
            $className = $optionDetails['class'];
            $registerName = (isset($optionDetails['register_name'])) ? $optionDetails['register_name'] : '';
            $paramName = (isset($optionDetails['param_name'])) ? $optionDetails['param_name'] : '';
            $outputValue = $optionDetails['output_value'];
            
            $model = $this->loadModel($className, $registerName, $paramName);
            
            if ($model) {
                return $this->getPredefinedValueOutput($outputValue, $model);
            }
        }

        return $options;
    }
    
    /**
     * @param string $className
     * @param string $registerName
     * @param string $paramName
     * @return mixed
     */
    protected function loadModel($className, $registerName, $paramName)
    {
        $model = $this->loadModelByRegister($registerName);
        if (!$model) {
            $model = $this->loadModelByParam($className, $paramName);
        }
        return $model;
    }
    
    /**
     * @param string $registerName
     * @return mixed
     */
    protected function loadModelByRegister($registerName)
    {
        return false;
        if (!empty($registerName)) {
            $model = $this->registry->registry($registerName);
            if ($model !== null && $model->getId()) {
                return $model;
            }
        }

        return false;
    }
    
    /**
     * @param string $className
     * @param string $paramName
     * @return mixed
     */
    protected function loadModelByParam($className, $paramName)
    {
        if (!empty($paramName)) {
            $paramValue = $this->getRequest()->getParam($paramName);
            $model = $this->autofillFactory->create($className);

            if (!empty($paramValue)) {
                $model->load($paramValue);
            }
            if ($model->getId()) {
                return $model;
            }
        }

        return false;
    }
    
    /**
     * @param string                        $template
     * @param \Magento\Framework\DataObject $dataObject
     * @return string
     */
    protected function getPredefinedValueOutput($template, $dataObject)
    {
        preg_match_all('/%([a-zA-Z_]+)/', $template, $variables);
        
        $pregKeys = [
            'full',
            'dataKey',
        ];
        
        $output = $template;
        
        if (isset($variables[0]) && !empty($variables[0])) {
            $vars = $this->translitPregMatchOutput($variables, $pregKeys);
            foreach ($vars as $var) {
                $regex = '/' . $var['full'] . '/';
                $replacement = $dataObject->getData($var['dataKey']);
                if (!$replacement) {
                    $replacement = '';
                }
                $output = preg_replace($regex, $replacement, $output);
            }
        }
        
        return $output;
    }
    
    /**
     * @param array $output
     * @param array $keys
     * @return array
     */
    protected function translitPregMatchOutput($output, $keys = [])
    {
        $translitted = [];
        foreach ($output as $dataKey => $matches) {
            foreach ($matches as $matchKey => $matchValue) {
                if (!isset($translitted[$matchKey])) {
                    $translitted[$matchKey] = [];
                }
            
                if (!empty($keys)) {
                    $translitted[$matchKey][$keys[$dataKey]] = $matchValue;
                } else {
                    $translitted[$matchKey][$dataKey] = $matchValue;
                }
            }
        }

        return $translitted;
    }
    
    /**
     * @return string
     */
    public function getJsValidation()
    {
        $jsValidators = [];
        
        if ($this->getField()->getIsRequired()) {
            $jsValidators['required'] = 'true';
        }
        
        $validationClass = $this->getField()->getValidation();
        if ($validationClass && !empty($validationClass::JS_VALIDATOR)) {
            $jsValidators["'".$validationClass::JS_VALIDATOR."'"] = 'true';
        }
        
        $validatorString = '{';
        foreach ($jsValidators as $validator => $enabled) {
            $validatorString .= $validator . ': ' . $enabled . ', ';
        }
        $validatorString = rtrim($validatorString, ', ') . '}';
        
        return $validatorString;
    }
    
    /**
     * @return string
     */
    public function getSetting($field, $group = 'general', $section = 'etailors_forms')
    {
        return $this->configurationHelper->getSetting($field, $group, $section);
    }
}
