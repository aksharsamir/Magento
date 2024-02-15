<?php

namespace Etailors\Forms\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Widget\Block\BlockInterface;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Model\EmailFactory;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Helper\Session as SessionHelper;
use Etailors\Forms\Helper\Template as TemplateHelper;

class Form extends Template implements BlockInterface, IdentityInterface
{
	
	const CACHE_TAG = 'etailors_forms_widget';
	
	/**
	 * @var FormFactory
	 */
	protected $formFactory;
	
	protected $emailFactory;
	
	protected $urlHelper;
	
	protected $sessionHelper;
	
	protected $templateHelper;
	
	protected $_isScopePrivate = true;
	
	/**
	 * @param TemplateContext $context
	 * @param FormFactory $formFactory
	 */
	public function __construct(
		TemplateContext $context,
		FormFactory $formFactory,
		EmailFactory $emailFactory,
		UrlHelper $urlHelper,
		SessionHelper $sessionHelper,
		TemplateHelper $templateHelper,
		array $data = []
	) {
		$this->formFactory = $formFactory;
		$this->emailFactory = $emailFactory;
		$this->urlHelper = $urlHelper;
		$this->sessionHelper = $sessionHelper;
		$this->templateHelper = $templateHelper;
		parent::__construct($context, $data);
	}
	
	protected function _construct()
	{
		$this->addData(
			[
				'cache_lifetime' => null,
				'cache_tags' => [self::CACHE_TAG . '_' . time()],
			]
		);
	}
	
	public function getIdentities() {
        return [self::CACHE_TAG . '_' . time()];
    }
	
	public function getCacheLifetime()
	{
		return false;
	}
	
	/**
	 * Set the template from form
	 */
	public function _prepareLayout(){
		
		parent::_prepareLayout();
	}
	
	public function _toHtml()
	{
		$form = $this->getForm();
		$emailId = $this->sessionHelper->getEmailId();
		
		// If from has been send, show thank you page
		if ($emailId !== 0) {
			$rawContent = $form->getThankYouPageContent();
			$email = $this->emailFactory->create()->load($emailId);
			$processedContent = $this->templateHelper->processRaw($rawContent, $form, $email);
			
			$this->sessionHelper->unsetEmailId();
			$this->sessionHelper->unsetValidationResults();
			
			return $processedContent;
			exit();
		}
		
		$formTemplate = $form->getTemplate();
		if (strpos($formTemplate, '::') === false) {
			$formTemplate = 'Etailors_Forms::' . $formTemplate;
		}
		$this->setTemplate($formTemplate);

		return parent::_toHtml();
	}
	
	public function getForm() 
	{
		$formId = $this->getFormId();
		$form = $this->formFactory->create()->load($formId);

		return $form;
	}
	
	public function getPostUrl() 
	{
		return $this->urlHelper->getCurrentPageUrl($this->getForm(), false, true);
	}
	
	public function getContent() 
	{
		$form = $this->getForm();
		
		$html = '';
		foreach ($form->getPages() as $page) {
			$pageBlock = $this->addChild(
				'etailors_forms_form_page_'.$page->getId(), 
				\Etailors\Forms\Block\Form\Page::class,
				['page' => $page]
			);
								
			$html .= $pageBlock->toHtml();
		}
		
		return $html;
	}

	public function displayPreviousButton() 
	{
		return false;
	}
	
	public function displaySubmitButton()
	{
		return true;
	}
} 
