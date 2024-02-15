<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Container;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class Fields extends Container implements TabInterface
{
    
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form_page_edit_tab_fields';
        $this->_blockGroup = 'Etailors_Forms';
        $this->_headerText = __('Pages');
        
        $this->_addNewButton();
        
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
    
    /**
     * Return Tab label
     *
     * @return string
     * @api
     */
    public function getTabLabel()
    {
        return __('Fields');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('Fields');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     * @api
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        return false;
    }
}
