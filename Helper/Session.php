<?php

namespace Etailors\Forms\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Etailors\Forms\Model\Session as SessionModel;

class Session extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SESSION_VALUES_KEY = 'etailors_forms_values';
    const SESSION_ERRORS_KEY = 'etailors_forms_errors';
    const SESSION_EMAILID_KEY = 'etailors_forms_email_id';
    
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    
    /**
     * @var SessionModel
     */
    protected $session;
    
    /**
     * @var \Etailors\Forms\Model\FormFactory
     */
    protected $formFactory;
    
    /**
     * @var \Etailors\Forms\Model\Form\PageFactory
     */
    protected $pageFactory;
    
    /**
     * @var \Etailors\Forms\Model\Form\Page\FieldFactory
     */
    protected $fieldFactory;
    
    /**
     * @var array
     */
    protected $values = [];
    
    /**
     * @var array
     */
    protected $errors = [];
    
    /**
     * @var array
     */
    protected $validationResults;
    
    /**
     * @param Context                                      $context
     * @param CustomerSession                              $customerSession
     * @param SessionModel                                 $session
     * @param \Etailors\Forms\Model\FormFactory            $formFactory
     * @param \Etailors\Forms\Model\Form\PageFactory       $pageFactory
     * @param \Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory
     * @return void
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        SessionModel $session,
        \Etailors\Forms\Model\FormFactory $formFactory,
        \Etailors\Forms\Model\Form\PageFactory $pageFactory,
        \Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory
    ) {
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->formFactory = $formFactory;
        $this->pageFactory = $pageFactory;
        $this->fieldFactory = $fieldFactory;
        $this->_init();
        parent::__construct($context);
    }
    
    /**
     * @return void
     */
    private function _init()
    {
    
        if ($this->customerSession->hasValidationResults()) {
            $this->validationResults = $this->customerSession->getValidationResults();
        }
        if ($this->customerSession->hasData(self::SESSION_VALUES_KEY)) {
            $rawValues = $this->customerSession->getData(self::SESSION_VALUES_KEY);
            $this->values = $this->decode($rawValues);
        }
        if ($this->customerSession->hasData(self::SESSION_ERRORS_KEY)) {
            $rawValues = $this->customerSession->getData(self::SESSION_ERRORS_KEY);
            $this->errors = $this->decode($rawValues);
        }
    }
    
    /**
     * @param integer|string $emailId
     * @return void
     */
    public function setEmailId($emailId)
    {
        $this->customerSession->setData(self::SESSION_EMAILID_KEY, $emailId);
    }
    
    /**
     * @return void
     */
    public function unsetEmailId()
    {
        if ($this->customerSession->hasData(self::SESSION_EMAILID_KEY)) {
            $this->customerSession->setData(self::SESSION_EMAILID_KEY, 0);
        }
    }
    
    /**
     * @return integer|string
     */
    public function getEmailId()
    {
        if ($this->customerSession->hasData(self::SESSION_EMAILID_KEY)) {
            return $this->customerSession->getData(self::SESSION_EMAILID_KEY);
        }

        return 0;
    }
    
    /**
     * @param \Etailors\Forms\Model\Form\Page\Field $field
     * @param mixed                                 $value
     * @return void
     */
    public function setFieldValue($field, $value)
    {
        if (is_int($field)) {
            $field = $this->fieldFactory->create()->load($field);
        }
        $fieldValue = [
            'form_id' => $field->getForm()->getId(),
            'page_id' => $field->getPage()->getId(),
            'field_id' => $field->getId(),
            'answer' => $value,
        ];
        $this->values[$field->getId()] = $fieldValue;
        $this->customerSession->setData(self::SESSION_VALUES_KEY, $this->encode($this->values));
    }
    
    /**
     * @param integer|\Etailors\Forms\Model\Form\Page\Field $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        
        if (is_int($field)) {
            $field = $this->fieldFactory->create()->load($field);
        }
        if ($this->validationResults !== null && isset($this->validationResults[$field->getId()])) {
            $fieldValidation = $this->validationResults[$field->getId()];
            return $fieldValidation->getValue();
        }
        
        if (isset($this->values[$field->getId()])) {
            return $this->values[$field->getId()]['answer'];
        }

        return false;
    }
    
    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
     * @return void
     */
    public function unsetValues()
    {
        $this->values = [];
        $this->customerSession->setData(self::SESSION_VALUES_KEY, $this->encode([]));
    }
    
    /**
     * @return void
     */
    public function unsetErrors()
    {
        $this->errors = [];
        $this->customerSession->setData(self::SESSION_VALUES_KEY, $this->encode([]));
    }
    
    /**
     * @return void
     */
    public function unsetValidationResults()
    {
        $this->customerSession->unsValidationResults();
    }
    
    /**
     * @return void
     */
    public function setFieldError($field, $errorMsg)
    {
        if (is_int($field)) {
            $field = $this->fieldFactory->create()->load($field);
        }
        $fieldError = [
            'form_id' => $field->getForm()->getId(),
            'page_id' => $field->getPage()->getId(),
            'field_id' => $field->getId(),
            'error' => $errorMsg
        ];
        $this->errors[$field->getId()] = $fieldError;
        $this->customerSession->setData(self::SESSION_ERRORS_KEY, $this->encode($this->values));
    }
    
    /**
     * @param integer|\Etailors\Forms\Model\Form\Page\Field $field
     * @return boolean|string
     */
    public function getFieldError($field)
    {
        if (is_int($field)) {
            $field = $this->fieldFactory->create()->load($field);
        }
        
        if ($this->validationResults !== null) {
            $fieldValidation = $this->validationResults[$field->getId()];
            $errors = $fieldValidation->getErrors();
            return $errors[0]->getMessage();
        }
        
        if (isset($this->errors[$field->getId()])) {
            return $this->errors[$field->getId()]['error'];
        }

        return false;
    }
    
    /**
     * @param integer|\Etailors\Forms\Model\Form\Page $page
     * @return array
     */
    public function getPageError($page)
    {
        if (is_int($page)) {
            $page = $this->pageFactory->create()->load($page);
        }
        $pageErrors = [];
        foreach ($this->errors as $error) {
            if ($error['page_id'] == $page->getId()) {
                $pageErrors[] = $error['error'];
            }
        }

        return $pageErrors;
    }
    
    /**
     * @param integer|\Etailors\Forms\Model\Form $form
     * @return array
     */
    public function getFormErrors($form)
    {
        if (is_int($form)) {
            $form = $this->formFactory->create()->load($form);
        }
        $formErrors = [];
        foreach ($this->errors as $error) {
            if ($error['form_id'] == $form->getId()) {
                $formErrors[] = $error['error'];
            }
        }

        return $formErrors;
    }
    
    /**
     * @param string $value
     * @return array
     */
    protected function decode($value)
    {
        return unserialize(base64_decode($value));
    }
    
    /**
     * @param array $value
     * @return string
     */
    protected function encode($value)
    {
        return base64_encode(serialize($value));
    }
}
