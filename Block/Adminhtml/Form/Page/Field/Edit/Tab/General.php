<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;

/**
 * Adminhtml staff edit form
 */
class General extends Generic implements TabInterface
{

    /**
     * @var FieldFactory
     */
    protected $fieldFactory;
    
    /**
     * @var \Etailors\Forms\Model\Config\Data\Template
     */
    protected $templateConfig;
    
    /**
     * @var \Etailors\Forms\Model\Config\Data\Autofill
     */
    protected $autfillClassConfig;
    
    /**
     * @param \Magento\Backend\Block\Template\Context    $context
     * @param FieldFactory                               $fieldFactory
     * @param \Magento\Framework\Registry                $registry
     * @param \Magento\Framework\Data\FormFactory        $formFactory
     * @param \Etailors\Forms\Model\Config\Data\Template $templateConfig
     * @param \Etailors\Forms\Model\Config\Data\Autofill $autfillClassConfig
     * @param array                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        FieldFactory $fieldFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Etailors\Forms\Model\Config\Data\Template $templateConfig,
        \Etailors\Forms\Model\Config\Data\Autofill $autfillClassConfig,
        array $data = []
    ) {
        $this->templateConfig = $templateConfig;
        $this->autfillClassConfig = $autfillClassConfig;
        $this->fieldFactory = $fieldFactory;
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
        $this->setId('grid_field');
        $this->setTitle(__('Page Information'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magebuzz\Staff\Model\Grid $model */
        $model = $this->_coreRegistry->registry('field_grid');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $this->setForm($form);
        
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('field_id', 'hidden', ['name' => 'field_id', 'value' => $model->getId()]);
        }
 
        $fieldset->addField(
            'title',
            'text',
            [
                'label' => __('Title'),
                'title' => __('Title'),
                'name' => 'title',
                'required' => true,
            ]
        );
        
        $fieldset->addField(
            'type',
            'select',
            [
                'label' => __('Type'),
                'title' => __('Type'),
                'name' => 'type',
                'required' => true,
                'values' => [
                    ['value' => 'textfield', 'label' => 'Text Field'],
                    ['value' => 'textarea', 'label' => 'Text Area'],
                    ['value' => 'select', 'label' => 'Dropdown'],
                    ['value' => 'radio', 'label' => 'Radio'],
                    ['value' => 'check', 'label' => 'Checkbox'],
                    ['value' => 'hidden', 'label' => 'Hidden'],
                    ['value' => 'recaptcha', 'label' => 'Recaptcha']
                ]
            ]
        );
        
        $fieldset->addField(
            'options_textarea',
            'textarea',
            [
                'label' => __('Options'),
                'title' => __('Options'),
                'name' => 'options',
                'required' => false,
                'note' => __('For Dropdown, radio and checkbox only. One option per line')
            ]
        );
        
        $fieldset->addField(
            'options_textfield',
            'text',
            [
                'label' => __('Default value'),
                'title' => __('Default value'),
                'name' => 'hidden_value',
                'required' => false,
                'value' => $model->getOptions(),
                'note' => __('This is the textual value for the hidden field')
            ]
        );
        
        $autofillClasses = $this->autfillClassConfig->toOptionArray();
        
        $fieldset->addField(
            'options_classSelect',
            'select',
            [
                'label' => __('Autofill with'),
                'title' => __('Autofill with'),
                'name' => 'options',
                'required' => false,
                'values' => $autofillClasses,
                'value' => $model->getOptions(),
                'note' => __('Fill value for hidden field with ID of this object. Must be present on page!')
            ]
        );
        
        $fieldset->addField(
            'sort_order',
            'text',
            [
                'label' => __('Sort order'),
                'title' => __('Sort order'),
                'name' => 'sort_order',
                'required' => true,
            ]
        );
        
        $fieldset->addField(
            'template',
            'select',
            [
                'label' => __('Template'),
                'title' => __('Template'),
                'name' => 'template',
                'required' => true,
                'values' => $this->templateConfig->toOptionArray('field')
            ]
        );
        
        $fieldset->addField(
            'display_in_overview',
            'checkbox',
            [
                'label' => __('Show answer in armin overview?'),
                'title' => __('Show answer in armin overview?'),
                'name' => 'display_in_overview',
                'required' => false,
                'checked' => ($model->getDisplayInOverview() == 1) ? true : false,
                'onchange' => 'this.value = this.checked;'
            ]
        );
        
        $refField = $this->fieldFactory->create(
            ['fieldData' => ['value' => 'select,check,radio', 'separator' => ','], 'fieldPrefix' => '']
        );

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            )->addFieldMap(
                "type",
                'type'
            )
                ->addFieldMap(
                    "options_textarea",
                    'options_textarea'
                )
                ->addFieldMap(
                    "options_textfield",
                    'options_textfield'
                )
                ->addFieldMap(
                    "options_classSelect",
                    'options_classSelect'
                )
                ->addFieldDependence(
                    'options_textarea',
                    'type',
                    $refField
                )
                ->addFieldDependence(
                    'options_textfield',
                    'type',
                    'hidden'
                )
                ->addFieldDependence(
                    'options_classSelect',
                    'type',
                    'hidden'
                )
        );
        
        if ($model->getType() == 'hidden') {
            $fieldAppend = 'textfield';
            foreach ($autofillClasses as $autofillClass) {
                if ($autofillClass['value'] == $model->getOptions()) {
                    $fieldAppend = 'classSelect';
                }
            }
            $model->setData('options_' . $fieldAppend, $model->getOptions());
        } else {
            $model->setData('options_textarea', $model->getOptions());
        }
        
        $form->setValues($model->getData());
        
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
        return __('General');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('General');
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