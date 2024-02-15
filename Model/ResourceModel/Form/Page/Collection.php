<?php

namespace Etailors\Forms\Model\ResourceModel\Form\Page;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            'Etailors\Forms\Model\Form\Page',
            'Etailors\Forms\Model\ResourceModel\Form\Page'
        );
    }
}
