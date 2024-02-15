<?php

namespace Etailors\Forms\Model;

use Magento\Framework\Model\AbstractModel;

class Email extends AbstractModel 
{	
	/**
	 * @var \Etailors\Forms\Model\AnswerFactory
	 */
	protected $answerFactory;
	
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
        \Etailors\Forms\Model\ResourceModel\Email $resource = null,
        \Etailors\Forms\Model\ResourceModel\Email\Collection $resourceCollection = null,
		\Etailors\Forms\Model\AnswerFactory $answerFactory,
        array $data = []
    ) {
		$this->answerFactory = $answerFactory;
		parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}
	
	protected function _construct() {
		$this->_init(\Etailors\Forms\Model\ResourceModel\Email::class);

	}
	
	public function getFieldAnswer($field) 
	{
		return $this->answerFactory->create()->getCollection()
			->addFieldToFilter('email_id', $this->getId())
			->addFieldToFilter('field_id', $field->getid())
			->getFirstItem()->getAnswer();
	}
	
	public function getAnswers() 
	{
		return $this->answerFactory->create()->getCollection()
			->addFieldToFilter('email_id', $this->getId());
	}
}