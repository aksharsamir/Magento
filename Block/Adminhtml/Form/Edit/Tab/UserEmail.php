<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class UserEmail extends Generic implements TabInterface
{

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     */
    protected $wysiwygConfig;
    
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config       $wysiwygConfig
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {

        $this->wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_form');
        $this->setTitle(__('Form Information'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magebuzz\Staff\Model\Grid $model */
        $model = $this->_coreRegistry->registry('form_grid');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('User Email'), 'class' => 'fieldset-wide']
        );
        
        $fieldset->addField(
            'user_email_enabled',
            'checkbox',
            [
                'label' => __('Enable email to user?'),
                'title' => __('Enable email to user?'),
                'name' => 'user_email_enabled',
                'required' => false,
                'onchange' => 'this.value = this.checked;',
                'checked' => ($model->getUserEmailEnabled() == 1) ? true : false
            ]
        );
        
        $fieldset->addField(
            'user_email_email',
            'text',
            [
                'label' => __('Emailaddress to send email from?'),
                'title' => __('Emailaddress to send email from?'),
                'name' => 'user_email_email',
            ]
        );
        
        $fieldset->addField(
            'user_email_name',
            'text',
            [
                'label' => __('User email name'),
                'title' => __('User email name'),
                'name' => 'user_email_name',
            ]
        );

        $fieldset->addField(
            'user_email_subject',
            'text',
            [
                'label' => __('Email subject'),
                'title' => __('Email subject'),
                'name' => 'user_email_subject',
            ]
        );
        
        $fieldset->addField(
            'user_email_content',
            'editor',
            [
                'label' => __('Email Content'),
                'title' => __('Email Content'),
                'name' => 'user_email_content',
                'required' => true,
                'class' => 'required',
                'wysiwyg' => true,
                'rows' => '15',
                'cols' => '30',
                'config' => $this->wysiwygConfig->getConfig(),
            ]
        );
        
        $form->setValues($model->getData());
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
    
    /**
     * Return Tab label
     *
     * @return string
     * @api
     */
    public function getTabLabel()
    {
        return __('User Email');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('User Email');
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
