<?php

namespace Etailors\Forms\Helper\Validator;

class ValidatorFactory 
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
	
	public function create($validator, array $data = []) 
	{
		if (!isset($this->instances[$validator])) {
			$class = $this->_objectManager->create($validator, $data);
			$this->instances[$validator] = $class;
		}

		return $this->instances[$validator];
	}
}