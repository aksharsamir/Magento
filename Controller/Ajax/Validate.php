<?php

namespace Etailors\Forms\Controller\Ajax;

use Etailors\Forms\Helper\Email;
use Etailors\Forms\Helper\Processor;
use Etailors\Forms\Model\FormFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class Validate extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var FormFactory
     */
    protected $formFactory;
    
    /**
     * @var Processor
     */
    protected $processor;
    
    /**
     * @var Email
     */
    protected $emailHelper;
    
    /**
     * @var JsonFactory
     */
    protected $jsonResultFactory;
    
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @param Context     $context
     * @param Processor   $processor
     * @param Email       $emailHelper
     * @param FormFactory $formFactory
     * @param JsonFactory $jsonResultFactory
     * @param PageFactory $resultPageFactory
     * @return void
     */
    public function __construct(
        Context $context,
        Processor $processor,
        Email $emailHelper,
        FormFactory $formFactory,
        JsonFactory $jsonResultFactory,
        PageFactory $resultPageFactory
    ) {
        $this->processor = $processor;
        $this->emailHelper = $emailHelper;
        $this->formFactory = $formFactory;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $this->processor->processFormValues();
        
        if ($this->processor->hasErrors()) {
            $results = $this->processor->getValidationResults();
    
            $returnData = [
                'valid' => false,
                'errors' => []
            ];
            
            foreach ($results as $fieldId => $result) {
                $returnData['errors'][$fieldId] = [
                    'valid' => $result->getIsValid(),
                    'value' => $result->getValue(),
                    'errors' => []
                ];
                foreach ($result->getErrors() as $error) {
                    $returnData['errors'][$fieldId]['errors'][] = __($error->getMessage());
                }
            }
        } else {
            $currentPage = $this->getRequest()->getParam('current_page');
            $numPages = $this->getForm()->getPagesCount();
            
            if ((int)$currentPage < (int)$numPages) {
                $newPage = (int)$currentPage + 1;
                $returnData = [
                    'valid' => true,
                    'success' => false,
                    'fields_html' => $this->collectNewPageHtml($newPage),
                    'buttons_html' => $this->collectNewButtonsHtml($newPage),
                    'newpage' => $newPage
                ];
            } else {
                $this->submitValues();
                $returnData = [
                    'valid' => true,
                    'success' => true,
                    'success_html' => $this->collectSuccessMessageHtml()
                ];
            }
        }

        $result = $this->jsonResultFactory->create();
        $result->setData($returnData);
        return $result;
    }
    
    /**
     * @return \Etailors\Forms\Model\Form
     */
    public function getForm()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $form = $this->formFactory->create()->load($formId);

        return $form;
    }
    
    /**
     * @param integer|sting $newPageNum
     * @return string
     */
    protected function collectNewPageHtml($newPageNum)
    {
        $fieldsBlock = $this->resultPageFactory->create()->getLayout()->createBlock(
            \Etailors\Forms\Block\Form\Fields::class,
            'etailors_forms_form_fields',
            ['data' => ['page_num' => $newPageNum]]
        );
            
        return $fieldsBlock->toHtml();
    }
    
    /**
     * @param integer|sting $newPageNum
     * @return string
     */
    protected function collectNewButtonsHtml($newPageNum)
    {
        $buttonsBlock = $this->resultPageFactory->create()->getLayout()->createBlock(
            \Etailors\Forms\Block\Form\Buttons::class,
            'etailors_forms_form_buttons',
            ['data' => ['page_num' => $newPageNum]]
        );
            
        return $buttonsBlock->toHtml();
    }
    
    /**
     * @return string
     */
    protected function collectSuccessMessageHtml()
    {
        $messageBlock = $this->resultPageFactory->create()->getLayout()->createBlock(
            \Etailors\Forms\Block\Form\Submit::class,
            'etailors_forms_form_success'
        );
            
        return $messageBlock->toHtml();
    }
    
    /**
     * @return string
     */
    protected function submitValues()
    {
        $form = $this->getForm();
        $email = null;
        try {
            $email = $this->processor->saveValues();
            $this->emailHelper->sendEmails($form, $email);
            
            return $email;
        } catch (\Exception $e) {
            //var_dump($e->getMessage());
        }
        return $email;
    }
}
