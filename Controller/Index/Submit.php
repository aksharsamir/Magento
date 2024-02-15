<?php

namespace Etailors\Forms\Controller\Index;

class Submit extends \Magento\Framework\App\Action\Action {
	
	protected $_pageFactory;
	
	protected $formFactory;
	
	protected $processor;
	
	protected $session;
	
	protected $emailHelper;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		 \Etailors\Forms\Model\FormFactory $formFactory,
		 \Etailors\Forms\Helper\Processor $processor,
		 \Etailors\Forms\Helper\Email $emailHelper,
		 \Magento\Customer\Model\Session $session
	)
	{
		$this->_pageFactory = $pageFactory;
		$this->session = $session;
		
		return parent::__construct($context);
	}

	public function execute()
	{
		$this->session->unsValidationResults();
		$this->session->unsetValidationResults();
		$resultPage = $this->_pageFactory->create();
		$resultPage->getConfig()->getTitle()->set($this->getFormTitle());

		return $resultPage;
	}
	
	protected function getFormTitle() 
	{
		$formId = $this->getRequest()->getParam('form_id');
		$form = $this->formFactory->create()->load($formId);
		if ($form->getId()) {
			return $form->getTitle();
		}
	}
	
}