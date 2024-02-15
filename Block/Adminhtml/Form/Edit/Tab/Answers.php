<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Container;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class Answers extends Container implements TabInterface
{
    
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form_edit_tab_answers';
        $this->_blockGroup = 'Etailors_Forms';
        $this->_headerText = __('Answers');
        
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
        return __('Answers');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('Answers');
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
