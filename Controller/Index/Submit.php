<?php

namespace Etailors\Forms\Controller\Index;

class Submit extends \Magento\Framework\App\Action\Action
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
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Etailors\Forms\Model\FormFactory          $formFactory
     * @param \Etailors\Forms\Helper\Processor           $processor
     * @param \Etailors\Forms\Helper\Email               $emailHelper
     * @param \Magento\Customer\Model\Session            $session
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Etailors\Forms\Model\FormFactory $formFactory,
        \Etailors\Forms\Helper\Processor $processor,
        \Etailors\Forms\Helper\Email $emailHelper,
        \Magento\Customer\Model\Session $session
    ) {
        $this->pageFactory = $pageFactory;
        $this->formFactory = $formFactory;
        $this->processor = $processor;
        $this->emailHelper = $emailHelper;
        $this->session = $session;
        
        return parent::__construct($context);
    }
    
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->session->unsValidationResults();
        $this->session->unsetValidationResults();
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set($this->getFormTitle());

        return $resultPage;
    }
    
    /**
     * @return string
     */
    protected function getFormTitle()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $form = $this->formFactory->create()->load($formId);
        if ($form->getId()) {
            return $form->getTitle();
        }
    }
}
