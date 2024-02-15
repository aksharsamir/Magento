<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class AdminEmail extends Generic implements TabInterface
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
        $model = $this->_coreRegistry->registry('form_grid');
 
        $form = $this->_formFactory->create();
        
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Admin Email'), 'class' => 'fieldset-wide']
        );
 
        $fieldset->addField(
            'admin_email_email',
            'text',
            [
                'label' => __('Where to send email to?'),
                'title' => __('Where to send email to?'),
                'name' => 'admin_email_email',
                'required' => true,
                'class' => 'required',
            ]
        );
        
        $fieldset->addField(
            'admin_email_name',
            'text',
            [
                'label' => __('Admin email name'),
                'title' => __('Admin email name'),
                'name' => 'admin_email_name',
                'required' => true,
                'class' => 'required',
            ]
        );
        
        $fieldset->addField(
            'admin_email_subject',
            'text',
            [
                'label' => __('Admin email subject'),
                'title' => __('Admin email subject'),
                'name' => 'admin_email_subject',
                'required' => true,
                'class' => 'required',
            ]
        );
        
        $fieldset->addField(
            'admin_email_content',
            'editor',
            [
                'label' => __('Email Content'),
                'title' => __('Email Content'),
                'name' => 'admin_email_content',
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
        return __('Admin Email');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('Admin Email');
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
