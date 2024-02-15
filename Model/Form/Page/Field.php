<?php

namespace Etailors\Forms\Model\Form\Page;

use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel 
{
	/**
	 * @var \Etailors\Forms\Model\ResourceModel\Form\PageFactory
	 */
	protected $pageFactory;
	
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
        \Etailors\Forms\Model\ResourceModel\Form\Page\Field $resource = null,
        \Etailors\Forms\Model\ResourceModel\Form\Page\Field\Collection $resourceCollection = null,
		\Etailors\Forms\Model\Form\PageFactory $pageFactory,
        array $data = []
    ) {
		$this->pageFactory = $pageFactory;
		parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}
	
	protected function _construct() {
		$this->_init(\Etailors\Forms\Model\ResourceModel\Form\Page\Field::class);

	}
	
	public function getPage() 
	{
		$pageId = $this->getPageId();

		return $this->pageFactory->create()->load($pageId);
	}
	
	public function getForm() 
	{
		return $this->getPage()->getForm();
	}
}