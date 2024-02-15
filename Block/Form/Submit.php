<?php

namespace Etailors\Forms\Block\Form;

use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Template as TemplateHelper;
use Etailors\Forms\Helper\Session as SessionHelper;
use Etailors\Forms\Model\EmailFactory;

class Submit extends \Magento\Framework\View\Element\Template 
{
	/**
	 * @var FormFactory
	 */
	protected $formFactory;
	
	protected $templateHelper;
	
	protected $session;
	
	protected $sessionHelper;
	
	protected $emailFactory;
	
	/**
	 * @param TemplateContext $context
	 * @param FormFactory $formFactory
	 */
	public function __construct(
		TemplateContext $context,
		FormFactory $formFactory,
		TemplateHelper $templateHelper,
		SessionHelper $sessionHelper,
		EmailFactory $emailFactory,
		 \Etailors\Forms\Model\Session $session,
		array $data = []
	) {
		$this->formFactory = $formFactory;
		$this->session = $session;
		$this->sessionHelper = $sessionHelper;
		$this->templateHelper = $templateHelper;
		$this->emailFactory = $emailFactory;
		parent::__construct($context, $data);
	}
	
	public function getForm() 
	{
		$formId = $this->getRequest()->getParam('form_id');
		$form = $this->formFactory->create()->load($formId);

		return $form;
	}
	
	public function getContent() 
	{
		$form = $this->getForm();
		$rawContent = $form->getThankYouPageContent();
		
		$emailId = $this->sessionHelper->getEmailId();
		$email = $this->emailFactory->create()->load($emailId);
				
		$processedContent = $this->templateHelper->processRaw($rawContent, $form, $email);
		
		$this->session->unsValidationResults();
		$this->sessionHelper->unsetEmailId();
		
		return $processedContent;
	}
	
}
