<?php

namespace Etailors\Forms\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\IntegrationException;
use Magento\Store\Model\StoreManagerInterface;
use Etailors\Forms\Helper\Session;
use Etailors\Forms\Helper\Validator;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Model\Form\Page\FieldFactory;
use Etailors\Forms\Model\EmailFactory;
use Etailors\Forms\Model\AnswerFactory;

class Processor extends \Magento\Framework\App\Helper\AbstractHelper {
	
	protected $sessionHelper;
	
	protected $validator;
	
	protected $request;
	
	protected $form;
	
	protected $storeManager;
	
	protected $formFactory;
	
	protected $fieldFactory;
	
	protected $hasErrors = false;
	
	protected $validationResults = [];
	
	public function __construct(
		Context $context,
		Session $sessionHelper,
		Validator $validator,
		RequestInterface $request,
		StoreManagerInterface $storeManager,
		FormFactory $formFactory,
		FieldFactory $fieldFactory,
		EmailFactory $emailFactory,
		AnswerFactory $answerFactory
	) {
		$this->sessionHelper = $sessionHelper;
		$this->validator = $validator;
		$this->request = $request;
		$this->formFactory = $formFactory;
		$this->fieldFactory = $fieldFactory;
		$this->emailFactory = $emailFactory;
		$this->answerFactory = $answerFactory;
		$this->storeManager = $storeManager;
		
		$formId = $this->request->getParam('form_id');
		$this->form = $this->formFactory->create()->load($formId);
		
		parent::__construct($context);
	}
	
	public function processFormValues() 
	{
		// Make sure to Unset Email Id before adding values to session
		$this->sessionHelper->unsetEmailId();
		
		$formValues = $this->request->getParam('etailors_forms');

		$validationResults = [];
		
		foreach ($formValues as $fieldId => $formValue) {
			if (is_array($formValue)) {
				$formValue = implode(', ', $formValue);
			}
			$field = $this->fieldFactory->create()->load($fieldId);
			
			$this->sessionHelper->setFieldValue($field, $formValue);
			
			$validationResult = $this->processFormValue($this->form, $field, $formValue);

			$this->addValidationResult($fieldId, $validationResult);
			
			if (!$validationResult->getIsValid()) {
				$this->sessionHelper->setFieldError($field, $validationResult->getErrors()[0]->getMessage());
				$this->hasErrors = true;
			}
		}

		return $this->hasErrors;
	}
	
	public function getForm() {
		return $this->form;
	}		
	
	public function getRequest() {
		return $this->request;
	}
	
	public function hasErrors() 
	{
		return $this->hasErrors;
	}
	
	public function setHasErrors($hasErrors) {
		$this->hasErrors = $hasErrors;
	}
	
	public function addValidationResult($fieldId, $validationResult) {
		$this->validationResults[$fieldId] = $validationResult;
	}
	
	public function getValidationResults() 
	{
		return $this->validationResults;
	}
	
	public function getFieldValidationResult($field) 
	{
		if (is_int($field)) {
			$field = $this->fieldFactory->create()->load($field);
		}
		if (isset($this->validationResults[$field->getId()])) {
			return $this->validationResults[$field->getId()];
		}

		return null;
	}
	
	public function processFormValue($form, $field, $value) 
	{
		if ($field->getForm()->getId() !== $form->getId()) {
			throw new IntegrationException('Field does not belong to this form');
		}

		return $this->validator->validate($field, $value);
	}
	
	public function saveValues() 
	{	

		if (!empty($this->sessionHelper->getValues())) {
			$email = $this->createEmail($this->form);
			
			$this->sessionHelper->setEmailId($email->getId());
			
			foreach ($this->form->getFields() as $field) {
				$answer = $this->answerFactory->create();
				$answer->setEmailId($email->getId());
				$answer->setFieldId($field->getId());
				$answer->setAnswer($this->sessionHelper->getFieldValue($field));
				$answer->save();
				
				
				if ($field->getContainsEmail()) {
					$email->setEmail($this->sessionHelper->getFieldValue($field));
					$email->save();
				}
				
			}
			$this->sessionHelper->unsetErrors();		
			$this->sessionHelper->unsetValues();
						
			return $email;
		}

		return null;
	}
	
	protected function createEmail($form) 
	{
		$email = $this->emailFactory->create();
		$email->setFormId($form->getId());
		$email->setCreatedAt(date('Y-m-d H:i:s'));
		$email->setUpdatedAt(date('Y-m-d H:i:s'));
		$email->setStoreId($this->storeManager->getStore()->getId());
		$email->save();

		return $email;
	}
}