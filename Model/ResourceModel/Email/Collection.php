<?php

namespace Etailors\Forms\Model\ResourceModel\Email;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    
    /**
     * @var string
     */
    protected $_idFieldName = 'email_id';
    
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            'Etailors\Forms\Model\Email',
            'Etailors\Forms\Model\ResourceModel\Email'
        );
    }
}
