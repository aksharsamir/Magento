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
	protected $registry;
	
	protected $autofillConfig;
	
	protected $autofillFactory;
	
	protected $configurationHelper;
	
	/**
	 * @param TemplateContext $context
	 * @param Session $sessionHelper
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
	 */
	public function _prepareLayout()
	{
		parent::_prepareLayout();
		$fieldType = $this->getData('field')->getType();
		$fieldTemplate = 'Etailors_Forms::field/renderer/type/' . $fieldType . '.phtml';
		$this->setTemplate($fieldTemplate);
	}
	
	public function getOptions() 
	{
		return explode("\n",$this->getData('field')->getOptions());
	}
	
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
	
	protected function loadModel($className, $registerName, $paramName) 
	{
		$model = $this->loadModelByRegister($registerName);
		if (!$model) {
			$model = $this->loadModelByParam($className, $paramName);
		}
		return $model;
	}
	
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
				}else {
					$translitted[$matchKey][$dataKey] = $matchValue;
				}
			}
		}

		return $translitted;
	}
	
	public function getSetting($field, $group = 'general', $section = 'etailors_forms') {
		return $this->configurationHelper->getSetting($field, $group, $section);
	}
}