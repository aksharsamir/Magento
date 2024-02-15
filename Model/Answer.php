<?php

namespace Etailors\Forms\Model;

use Magento\Framework\Model\AbstractModel;

class Answer extends AbstractModel 
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
	 * @param \Etailors\Forms\Model\AnswerFactory $answerFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Etailors\Forms\Model\ResourceModel\Answer $resource = null,
        \Etailors\Forms\Model\ResourceModel\Answer\Collection $resourceCollection = null,
		\Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory,
        array $data = []
    ) {
		$this->fieldFactory = $fieldFactory;
		parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}
	
	protected function _construct() {
		$this->_init(\Etailors\Forms\Model\ResourceModel\Answer::class);

	}
	
	public function getField() 
	{
		$fieldId = $this->getFieldId();
		
		return $this->fieldFactory->create()->load($fieldId);
	}	
}