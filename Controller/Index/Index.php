<?php

namespace Etailors\Forms\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Etailors\Forms\Helper\Url as UrlHelper;

class Index extends \Magento\Framework\App\Action\Action
{
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;
    
    /**
     * @var MessageManagerInterface
     */
    protected $messageManager;
    
    /**
     * @var \Etailors\Forms\Model\FormFactory
     */
    protected $formFactory;
    
    /**
     * @var \Etailors\Forms\Helper\Processor
     */
    protected $processor;
    
    /**
     * @var \Etailors\Forms\Helper\Email
     */
    protected $emailHelper;
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    
    /**
     * @var UrlHelper
     */
    protected $urlHelper;
    
    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param MessageManagerInterface                    $messageManager
     * @param \Etailors\Forms\Model\FormFactory          $formFactory
     * @param \Etailors\Forms\Helper\Processor           $processor
     * @param \Etailors\Forms\Helper\Email               $emailHelper
     * @param \Magento\Customer\Model\Session            $session
     * @param UrlHelper                                  $urlHelper
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        MessageManagerInterface $messageManager,
        \Etailors\Forms\Model\FormFactory $formFactory,
        \Etailors\Forms\Helper\Processor $processor,
        \Etailors\Forms\Helper\Email $emailHelper,
        \Magento\Customer\Model\Session $session,
        UrlHelper $urlHelper
    ) {
        $this->messageManager = $messageManager;
        $this->pageFactory = $pageFactory;
        $this->formFactory = $formFactory;
        $this->processor = $processor;
        $this->emailHelper = $emailHelper;
        $this->session = $session;
        $this->urlHelper = $urlHelper;
        return parent::__construct($context);
    }
    
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('etailors_forms')) {
            $form = $this->getForm();
            $this->processor->processFormValues();
            $this->session->setValidationResults($this->processor->getValidationResults());
            
            if (!$this->processor->hasErrors()) {
                if ($this->urlHelper->getCurrentPage() == $this->getForm()->getPagesCount()) {
                    $email = $this->submitValues();
                    $this->_eventManager->dispatch('etailors_forms_submit_values_after', ['email' => $email]);
                }
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->getRedirectUrl());
                return $resultRedirect;
            }
           
            $referer = $this->getRequest()->getParam('referer');
            if ($referer) {
                $this->pageFactory->create()->setHeader(
                    'Cache-Control',
                    'no-store, no-cache, must-revalidate, max-age=0',
                    true
                );
                $refererUrl = base64_decode($referer);
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($refererUrl);
                return $resultRedirect;
            }
        }
        
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);
        $resultPage->getConfig()->getTitle()->set($this->getFormTitle());
        return $resultPage;
    }
    
    /**
     * @return \Etailors\Forms\Model\Form
     */
    protected function getForm()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $form = $this->formFactory->create()->load($formId);

        return $form;
    }
    
    /**
     * @return string
     */
    protected function getFormTitle()
    {
        $form = $this->getForm();
        if ($form->getId()) {
            return $form->getTitle();
        }
        return '';
    }
    
    /**
     * @return string
     */
    protected function getRedirectUrl()
    {
        $form = $this->getForm();
        if ($form->getTreatPagesAsSections()) {
            return $this->urlHelper->getSubmitUrl($form);
        } elseif ($this->urlHelper->getCurrentPage() == $form->getPagesCount()) {
            return $this->urlHelper->getSubmitUrl($form);
        }

        return $this->urlHelper->getNextPageUrl($form);
    }
    
    /**
     * @return string
     */
    protected function submitValues()
    {
        $form = $this->formFactory->create()
            ->load($this->getRequest()->getParam('form_id'));
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
