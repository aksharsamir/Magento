<?php

namespace Etailors\Forms\Block\Form;

use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Helper\Session as SessionHelper;
use Etailors\Forms\Helper\Processor;
use Magento\Customer\Model\Session;

class Field extends \Magento\Framework\View\Element\Template
{
    const CACHE_KEY = 'ETAILORS_FORMS_FIELD';
    
    const FIELD_PREFIX_NAME = 'etailors_forms';
    const FIELD_PREFIX_ID = 'etailors_forms_field_';
    
    /**
     * @var SessionHelper
     */
    protected $sessionHelper;
    
    /**
     * @var Processor
     */
    protected $processor;
    
     /**
      * @var Session
      */
    protected $session;
    
    /**
     * @param TemplateContext $context
     * @param SessionHelper   $sessionHelper
     * @param Processor       $processor
     * @param Session         $session
     * @param array           $data
     * @return void
     */
    public function __construct(
        TemplateContext $context,
        SessionHelper $sessionHelper,
        Processor $processor,
        Session $session,
        array $data = []
    ) {
        $this->sessionHelper = $sessionHelper;
        $this->processor = $processor;
        $this->session = $session;

        parent::__construct($context, $data);
    }
    
    /**
     * Keep cache key unique for each form
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            self::CACHE_KEY,
            $this->_storeManager->getStore()->getId(),
            $this->getField()->getId()
        ];
    }
    
    /**
     * Set the template from form
     * @return void
     */
    public function _prepareLayout()
    {
        $fieldTemplate = $this->getData('field')->getTemplate();
        if ($this->getData('field')->getType() == 'hidden') {
            // Force hidden field template
            $fieldTemplate = 'Etailors_Forms::field/hidden.phtml';
        }
        
        if (strpos($fieldTemplate, '::') === false) {
            $fieldTemplate = 'Etailors_Forms::' . $fieldTemplate;
        }
        $this->setTemplate($fieldTemplate);

        parent::_prepareLayout();
    }
    
    /**
     * @return string
     */
    public function renderInput()
    {
        $field = $this->getField();
        
        $inputBlock = $this->addChild(
            'etailors_forms_form_field_input_'.$field->getId(),
            \Etailors\Forms\Block\Form\Field\Renderer::class,
            ['field' => $field]
        );
        
        return $inputBlock->toHtml();
    }
    
    /**
     * @return string
     */
    public function getFieldName()
    {
        return self::FIELD_PREFIX_NAME . '[' . $this->getField()->getId() . ']';
    }
    
    /**
     * @return string
     */
    public function getFieldHtmlId()
    {
        return self::FIELD_PREFIX_ID . $this->getField()->getId();
    }
    
    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->sessionHelper->getFieldValue($this->getField());
    }
    
    /**
     * @return string
     */
    public function getFieldClasses()
    {
        $classes = [];
        if ($this->getField()->getType() !== 'radio' &&
            $this->getField()->getType() !== 'check'
        ) {
            $classes[] = 'form-control';
        }
        $classes[] = 'etailors-forms-field';
        $classes[] = 'etailors-forms-field-' . $this->getField()->getType();
        
        if ($this->getField()->getIsRequired() == 1) {
            $classes[] = 'required';
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * @return null|\Etailors\Forms\Model\Validation\Result
     */
    public function getFieldValidation()
    {
        $validationResults = $this->session->getValidationResults();
        return (isset($validationResults[$this->getField()->getId()]))
            ? $validationResults[$this->getField()->getId()]
            : null;
    }
    
    /**
     * @return boolean
     */
    public function isFieldValid()
    {
        $fieldValidation = $this->getFieldValidation();
        if ($fieldValidation !== null) {
            return $fieldValidation->getIsValid();
        }

        return true;
    }
    
    /**
     * @return array
     */
    public function getFieldErrors()
    {
        $fieldValidation = $this->getFieldValidation();
        if ($fieldValidation !== null) {
            return $fieldValidation->getErrors();
        }

        return [];
    }
}
