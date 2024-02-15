<?php

namespace Etailors\Forms\Block\Adminhtml;

class Form extends \Magento\Backend\Block\Widget\Grid\Container
{
    
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form';
        $this->_blockGroup = 'Etailors_Forms';
        $this->_headerText = __('Forms');
        
        parent::_construct();
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}
