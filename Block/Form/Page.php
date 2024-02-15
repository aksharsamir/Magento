<?php

namespace Etailors\Forms\Block\Form;

use \Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Url as UrlHelper;

class Page extends \Magento\Framework\View\Element\Template 
{
	/**
	 * @param TemplateContext $context
	 * @param FormFactory $formFactory
	 */
	public function __construct(
		TemplateContext $context,
		UrlHelper $urlHelper,
		array $data = []
	) {
		$this->urlHelper = $urlHelper;
		
		parent::__construct($context, $data);
	}
	
	/**
	 * Set the template from form
	 */
	public function _prepareLayout(){
		$pageTemplate = $this->getData('page')->getTemplate();
		if (strpos($pageTemplate, '::') === false) {
			$pageTemplate = 'Etailors_Forms::' . $pageTemplate;
		}
		$this->setTemplate($pageTemplate);

		parent::_prepareLayout();
	}

	public function getContent() {
		$page = $this->getPage();

		$html = '';
		foreach ($page->getFields() as $field) {
			$fieldBlock = $this->addChild(
				'etailors_forms_form_field_'.$field->getId(), 
				\Etailors\Forms\Block\Form\Field::class,
				['field' => $field]
			);
								
			$html .= $fieldBlock->toHtml();
		}
		
		return $html;
	}
}