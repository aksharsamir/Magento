<?php

namespace Etailors\Forms\Block;

use Magento\Framework\DataObject\IdentityInterface;
use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Model\Session;

class Form extends \Magento\Framework\View\Element\Template implements IdentityInterface
{	

	const CACHE_TAG = 'etailors_forms_form';

	/**
	 * @var FormFactory
	 */
	protected $formFactory;
	
	protected $session;
	
	/**
	 * @param TemplateContext $context
	 * @param FormFactory $formFactory
	 */
	public function __construct(
		TemplateContext $context,
		FormFactory $formFactory,
		UrlHelper $urlHelper,
		Session $session,
		array $data = []
	) {
		$this->formFactory = $formFactory;
		$this->urlHelper = $urlHelper;
		$this->session = $session;
		parent::__construct($context, $data);
	}
	
	protected function _construct()
	{
		$this->addData(
			[
				'cache_lifetime' => null,
				'cache_tags' => ['Etailors_Forms', \Magento\Store\Model\Group::CACHE_TAG],
			]
		);
	}
	
	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . time()];
    }
	
	public function getCacheLifetime()
	{
		return false;
	}
	
	/**
	 * Set the template from form
	 */
	public function _prepareLayout()
	{
		$form = $this->getForm();
		$formTemplate = $form->getTemplate();
		if (strpos($formTemplate, '::') === false) {
			$formTemplate = 'Etailors_Forms::' . $formTemplate;
		}
		$this->setTemplate($formTemplate);

		parent::_prepareLayout();
	}
	
	public function getForm() 
	{
		$formId = $this->getRequest()->getParam('form_id');
		$form = $this->formFactory->create()->load($formId);

		return $form;
	}
	
	public function getPostUrl() 
	{
		return $this->urlHelper->getCurrentPageUrl($this->getForm());
	}
	
	public function getPreviousPageUrl() 
	{
		return $this->urlHelper->getPreviousPageUrl($this->getForm());
	}
	
	public function getContent() 
	{
		$form = $this->getForm();
		if ($form->getTreatPagesAsSections()) {
			$html = '';
			foreach ($form->getPages() as $page) {
				$pageBlock = $this->addChild(
					'etailors_forms_form_page_'.$page->getId(), 
					\Etailors\Forms\Block\Form\Page::class,
					['page' => $page]
				);
									
				$html .= $pageBlock->toHtml();
			}
		}
		else {
			$page = $form->getPageByNum($this->urlHelper->getCurrentPage());
			$pageBlock = $this->addChild(
				'etailors_forms_form_page_'.$page->getId(), 
				\Etailors\Forms\Block\Form\Page::class,
				['page' => $page]
			);
				
			$html = $pageBlock->toHtml();
		}

		return $html;
	}
	
	public function getPreviousPage() 
	{
		return $this->urlHelper->getCurrentPage() - 1;
	}
	
	public function getNextPage() 
	{
		return $this->urlHelper->getCurrentPage() + 1;
	}
	
	public function displayPreviousButton() 
	{
		$form = $this->getForm();
		if ($form->getTreatPagesAsSections()) {
			return false;
		}
		elseif ($this->urlHelper->getCurrentPage() == 1) {
			return false;
		}

		return true;
	}
	
	public function displaySubmitButton() 
	{
		$form = $this->getForm();
		if ($form->getTreatPagesAsSections()) {
			return true;
		}
		elseif ($this->urlHelper->getCurrentPage() == $form->getPagesCount()) {
			return true;
		}

		return false;
	}	
}