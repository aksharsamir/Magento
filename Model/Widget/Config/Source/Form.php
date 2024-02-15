<?php

namespace Etailors\Forms\Model\Widget\Config\Source;

class Form implements \Magento\Framework\Option\ArrayInterface 
{
	protected $formFactory;
	
	public function __construct(
		\Etailors\Forms\Model\FormFactory $formFactory
	) {
		$this->formFactory = $formFactory;
	}
	
	public function toOptionArray() 
	{
		$optionArray = [];

		foreach ($this->getApplicableForms() as $form) {
			$optionArray[] = [
				'value' => $form->getId(),
				'label' => $form->getTitle()
			];
		}
		
		return $optionArray;
	}
	
	protected function getApplicableForms() 
	{
		// At this point, only forms with 
		// treatPagesAsSections = 1 are supported
		return $this->formFactory->create()->getCollection()
			->addFieldToFilter('treat_pages_as_sections', 1);
	}
	
}