<?php

namespace Etailors\Forms\Model\Widget\Config\Source;

class Form implements \Magento\Framework\Option\ArrayInterface
{
    
    /**
     * @var \Etailors\Forms\Model\FormFactory
     */
    protected $formFactory;
    
    /**
     * @param \Etailors\Forms\Model\FormFactory $formFactory
     * @return void
     */
    public function __construct(
        \Etailors\Forms\Model\FormFactory $formFactory
    ) {
        $this->formFactory = $formFactory;
    }
    
    /**
     * @return array
     */
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
    
    /**
     * @return \Etailors\Forms\Model\ResourceModel\Form\Collection
     */
    protected function getApplicableForms()
    {
        // At this point, only forms with
        // treatPagesAsSections = 1 are supported
        return $this->formFactory->create()->getCollection()
            ->addFieldToFilter('treat_pages_as_sections', 1);
    }
}
