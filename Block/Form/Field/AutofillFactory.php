<?php

namespace Etailors\Forms\Block\Form\Field;

class AutofillFactory 
{	
	/**
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $_objectManager;
	
	protected $instances = [];

	/**
	 * @param \Magento\Framework\ObjectManagerInterface $objectManager
	 */
	public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
	{
		$this->_objectManager = $objectManager;
	}
	
	public function create($autofillClass, array $data = []) 
	{	
		if (!isset($this->instances[$autofillClass])) {
			$class = $this->_objectManager->create($autofillClass, $data);
			$this->instances[$autofillClass] = $class;
		}

		return $this->instances[$autofillClass];
	}
}