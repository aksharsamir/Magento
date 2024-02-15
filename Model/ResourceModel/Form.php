<?php

namespace Etailors\Forms\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Form extends AbstractDb 
{
	/**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('etailors_forms_form', 'form_id');
    }
}