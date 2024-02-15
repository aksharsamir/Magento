<?php

namespace Etailors\Forms\Block\Form;

use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Helper\Session as SessionHelper;
use Etailors\Forms\Helper\Processor;
use Magento\Customer\Model\Session;

class Field extends \Magento\Framework\View\Element\Template 
{
	const FIELD_PREFIX_NAME = 'etailors_forms';
	const FIELD_PREFIX_ID = 'etailors_forms_field_';
	
	protected $_isScopePrivate = true;
	
	protected $sessionHelper;
	
	protected $processor;
	
	protected $session;
	
	
	/**
	 * @param TemplateContext $context
	 * @param Session $sessionHelper
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
	
	protected function _construct()
	{
		parent::_construct();
		$this->addData(array('cache_lifetime' => null));
	}
	
	/**
	 * Set the template from form
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
	
	public function getFieldName() 
	{
		return self::FIELD_PREFIX_NAME . '[' . $this->getField()->getId() . ']';
	}
	
	public function getFieldHtmlId() 
	{
		return self::FIELD_PREFIX_ID . $this->getField()->getId();
	}
	
	public function getValue() 
	{
		return $this->sessionHelper->getFieldValue($this->getField());
	}
	
	public function getFieldClasses() 
	{
		$classes = [];
		if (
			$this->getField()->getType() !== 'radio' && 
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
	
	public function getFieldValidation() {
		$validationResults = $this->session->getValidationResults();
		return (isset($validationResults[$this->getField()->getId()])) ? $validationResults[$this->getField()->getId()] : null;
	}
	
	public function isFieldValid() 
	{
		$fieldValidation = $this->getFieldValidation();
		if ($fieldValidation !== null) {
			return $fieldValidation->getIsValid();
		}

		return true;
	}
	
	public function getFieldErrors() 
	{
		$fieldValidation = $this->getFieldValidation();
		if ($fieldValidation !== null) {
			return $fieldValidation->getErrors();
		}

		return [];
	}
}