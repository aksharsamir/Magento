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

class Processor extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    /**
     * @var Session
     */
    protected $sessionHelper;
    
    /**
     * @var Validator
     */
    protected $validator;
    
    /**
     * @var RequestInterface
     */
    protected $request;
    
    /**
     * @var \Etailors\Forms\Model\Form
     */
    protected $form;
    
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    
    /**
     * @var FormFactory
     */
    protected $formFactory;
    
    /**
     * @var FieldFactory
     */
    protected $fieldFactory;
    
    /**
     * @var EmailFactory
     */
    protected $emailFactory;
    
    /**
     * @var AnswerFactory
     */
    protected $answerFactory;
    
    /**
     * @var boolean
     */
    protected $hasErrors = false;
    
    /**
     * @var array
     */
    protected $validationResults = [];
    
    /**
     * @param Context               $context
     * @param Session               $sessionHelper
     * @param Validator             $validator
     * @param RequestInterface      $request
     * @param StoreManagerInterface $storeManager
     * @param FormFactory           $formFactory
     * @param FieldFactory          $fieldFactory
     * @param EmailFactory          $emailFactory
     * @param AnswerFactory         $answerFactory
     * @return void
     */
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
    
    /**
     * @return boolean
     */
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
    
    /**
     * @return \Etailors\Forms\Model\Form
     */
    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->hasErrors;
    }
    
    /**
     * @param boolean $hasErrors
     * @return void
     */
    public function setHasErrors($hasErrors)
    {
        $this->hasErrors = $hasErrors;
    }
    
    /**
     * @param string                                  $fieldId
     * @param \Etailors\Forms\Model\Validation\Result $validationResult
     * @return void
     */
    public function addValidationResult($fieldId, $validationResult)
    {
        $this->validationResults[$fieldId] = $validationResult;
    }
    
    /**
     * @return array
     */
    public function getValidationResults()
    {
        return $this->validationResults;
    }
    
    /**
     * @return null|\Etailors\Forms\Model\Validation\Result
     */
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
    
    /**
     * @param \Etailors\Forms\Model\Form            $form
     * @param \Etailors\Forms\Model\Form\Page\Field $field
     * @param mixed                                 $value
     * @return \Etailors\Forms\Model\Validation\Result
     */
    public function processFormValue($form, $field, $value)
    {
        if ($field->getForm()->getId() !== $form->getId()) {
            throw new IntegrationException(__('Field does not belong to this form'));
        }

        return $this->validator->validate($field, $value);
    }
    
    /**
     * @return null|\Etailors\Forms\Model\Email
     */
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
    
    /**
     * @return \Etailors\Forms\Model\Email
     */
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
