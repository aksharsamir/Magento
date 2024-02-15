<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit;

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
        $this->setId('form_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Edit form'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'general',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\General'
                )->toHtml(),
                'active' => true
            ]
        );
        
        $this->addTab(
            'pages',
            [
                'label' => __('Pages'),
                'title' => __('Pages'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Pages'
                )->toHtml(),
                'active' => false
            ]
        );
        
        $this->addTab(
            'admin_email',
            [
                'label' => __('Admin Email'),
                'title' => __('Admin Email'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\AdminEmail'
                )->toHtml(),
                'active' => false
            ]
        );
        
        $this->addTab(
            'user_email',
            [
                'label' => __('User Email'),
                'title' => __('User Email'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\UserEmail'
                )->toHtml(),
                'active' => false
            ]
        );
        
        $this->addTab(
            'thank_you_page',
            [
                'label' => __('Thank You page'),
                'title' => __('Thank You page'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\ThankYouPage'
                )->toHtml(),
                'active' => false
            ]
        );
        
        $this->addTab(
            'emails',
            [
                'label' => __('Answers'),
                'title' => __('Answers'),
                'content' => $this->getLayout()->createBlock(
                    'Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Answers'
                )->toHtml(),
                'active' => false
            ]
        );

        return parent::_beforeToHtml();
    }
}
