<?php

namespace Etailors\Forms\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string                                     $namespace
     * @param array                                      $data
     * @return void
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $namespace = 'etailors_forms',
        array $data = []
    ) {
        parent::__construct($namespace, $data);
    }
}
