<?php

namespace Etailors\Forms\Helper\Validator;

class ValidatorFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;
    
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @return void
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
    /**
     * @param mixed $validator
     * @param array $data
     * @return mixed
     */
    public function create($validator, array $data = [])
    {
        if (!isset($this->instances[$validator])) {
            $class = $this->objectManager->create($validator, $data);
            $this->instances[$validator] = $class;
        }

        return $this->instances[$validator];
    }
}
