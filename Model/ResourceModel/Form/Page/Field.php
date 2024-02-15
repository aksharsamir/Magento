<?php

namespace Etailors\Forms\Model\ResourceModel\Form\Page;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Field extends AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('etailors_forms_field', 'field_id');
    }
}
