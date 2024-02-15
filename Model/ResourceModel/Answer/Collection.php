<?php

namespace Etailors\Forms\Model\ResourceModel\Answer;

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
			'Etailors\Forms\Model\Answer', 
			'Etailors\Forms\Model\ResourceModel\Answer'
		);
    }
	
}