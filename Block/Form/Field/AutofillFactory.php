<?php

namespace Etailors\Forms\Block\Form\Field;

class AutofillFactory
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
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
    /**
     * @param string $autofillClass
     * @param array  $data
     * @return object
     */
    public function create($autofillClass, array $data = [])
    {
        if (!isset($this->instances[$autofillClass])) {
            $class = $this->objectManager->create($autofillClass, $data);
            $this->instances[$autofillClass] = $class;
        }

        return $this->instances[$autofillClass];
    }
}
