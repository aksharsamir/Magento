<?php

namespace Etailors\Forms\Model\ResourceModel\Form;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Page extends AbstractDb
{

    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('etailors_forms_page', 'page_id');
    }
}
