<?php

namespace Etailors\Forms\Model\Form;

use Magento\Framework\Model\AbstractModel;

class Page extends AbstractModel 
{
	/**
	 * @var \Etailors\Forms\Model\Form\Page\FieldFactory
	 */
	protected $fieldFactory;
	
	/**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
	 * @param \Etailors\Forms\Model\ResourceModel\Form\PageFactory $pageFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Etailors\Forms\Model\ResourceModel\Form\Page $resource = null,
        \Etailors\Forms\Model\ResourceModel\Form\Page\Collection $resourceCollection = null,
		\Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory,
		\Etailors\Forms\Model\FormFactory $formFactory,
        array $data = []
    ) {
		$this->fieldFactory = $fieldFactory;
		$this->formFactory = $formFactory;
		parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}
	
	protected function _construct() {
		$this->_init(\Etailors\Forms\Model\ResourceModel\Form\Page::class);
	}
	
	public function getFields() 
	{
		return $this->fieldFactory->create()->getCollection()
			->addFieldToFilter('page_id', $this->getId())
			->setOrder('sort_order', 'ASC');
	}
	
	public function getForm() 
	{
		$formId = $this->getFormId();

		return $this->formFactory->create()->load($formId);
	}
}