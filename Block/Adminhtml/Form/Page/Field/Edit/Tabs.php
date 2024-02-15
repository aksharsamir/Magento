<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        
        $this->setId('field_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Edit field'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'field_general',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit\Tab\General'
                )->toHtml(),
                'active' => true
            ]
        );
        
        $this->addTab(
            'field_validation',
            [
                'label' => __('Validation'),
                'title' => __('Validation'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit\Tab\Validation'
                )->toHtml(),
                'active' => false
            ]
        );

        return parent::_beforeToHtml();
    }
}
