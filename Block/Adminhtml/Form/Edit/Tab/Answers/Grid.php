<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Answers;

use Etailors\Forms\Model\Config\Data\Autofill as AutofillConfig;
use Etailors\Forms\Block\Form\Field\AutofillFactory;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Etailors\Forms\Model\EmailFactory $emailFactory
     */
    protected $emailFactory;
    
    /**
     * @var \Etailors\Forms\Model\FormFactory $formFactory
     */
    protected $formFactory;
    
    /**
     * @var \AutofillFactory
     */
    protected $autofillFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data            $backendHelper
     * @param \Etailors\Forms\Model\FormFactory       $formFactory
     * @param \Etailors\Forms\Model\EmailFactory      $emailFactory
     * @param AutofillFactory                         $autofillFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Etailors\Forms\Model\FormFactory $formFactory,
        \Etailors\Forms\Model\EmailFactory $emailFactory,
        AutofillFactory $autofillFactory,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->emailFactory = $emailFactory;
        $this->autofillFactory = $autofillFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('emailGrid');
        $this->setDefaultSort('email_created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('email_filter');
    }

    /**
     * @return \Magento\Store\Model\Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $formId = $this->getRequest()->getParam('id');
        
        $collection = $this->emailFactory->create()->getCollection();
        $collection->addFieldToFilter('form_id', $formId);
        if ($formId) {
            $form = $this->formFactory->create()->load($formId);
            foreach ($form->getFields() as $field) {
                if ($field->getDisplayInOverview()) {
                    $this->joinFieldAnswer($collection, $field);
                }
            }
        }
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }
    
    /**
     * @param \Etailors\Forms\Model\ResourceModel\Email\Collection $collection
     * @param \Etailors\Forms\Model\Form\Page\Field                $field
     * @return void
     */
    protected function joinFieldAnswer($collection, $field)
    {
        $fieldAlias = $this->translitFieldName($field->getTitle());
        $tableAlias = $fieldAlias . '_table';
        
        $collection->getSelect()->joinLeft(
            [$tableAlias => $collection->getTable('etailors_forms_answer')],
            "`main_table`.`email_id` = `{$tableAlias}`.`email_id` AND `{$tableAlias}`.`field_id` = {$field->getId()}",
            [$fieldAlias => "{$tableAlias}.answer"]
        );
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            $field = $column->getFilterIndex() ? $column->getFilterIndex() : $column->getIndex();
            if ($column->getFilterConditionCallback()) {
                call_user_func($column->getFilterConditionCallback(), $this->getCollection(), $column);
            } else {
                $condition = $column->getFilter()->getCondition();
                if ($field && isset($condition)) {
                    $this->getCollection()->addFieldToFilter($field, $condition);
                }
            }
        }
        
        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $formId = $this->getRequest()->getParam('id');
        
        if ($formId) {
            $form = $this->formFactory->create()->load($formId);
            
            foreach ($form->getFields() as $field) {
                if ($field->getDisplayInOverview()) {
                    $fieldConfig = $this->getFieldConfig($field);
                    $this->addColumn(
                        'email_field_' . $field->getId(),
                        $fieldConfig
                    );
                }
            }
            
            $this->addColumn(
                'email_created_at',
                [
                    'header' => __('Created'),
                    'index' => 'created_at',
                    'dataType' => 'date',
                    'filter' => \Magento\Backend\Block\Widget\Grid\Column\Filter\Datetime::class,
                    'filter_index' => 'main_table.created_at',
                    'timezone' => 'false',
                    'dateFormat' => 'd-m-Y',
                    'showsTime' => true,
                    'class' => ''
                ]
            );
        }

        return parent::_prepareColumns();
    }
        
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/answer/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/answer/view',
            [
                'id' => $row->getId(),
                'form_id' => $this->getRequest()->getParam('id')
            ]
        );
    }
    
    /**
     * @param \Etailors\Forms\Model\Form\Page\Field $field
     * @return array
     */
    protected function getFieldConfig($field)
    {
        $fieldType = $this->getFieldDataType($field->getType());
        $fieldConfig = [
            'header' => __($field->getTitle()),
            'index' => $this->translitFieldName($field->getTitle()),
            'filter_index' => $this->translitFieldName($field->getTitle()) . '_table.answer',
            'dataType' => $this->getFieldDataType($field->getType())
        ];
        if ($field->getType() == 'select' || $field->getType() == 'radio') {
            $fieldConfig['filter'] = \Magento\Backend\Block\Widget\Grid\Column\Filter\Select::class;
            $fieldConfig['options'] = $this->getSelectOptions($field->getOptions());
        }

        return $fieldConfig;
    }
    
    /**
     * @param string $fieldOptionString
     * @return array
     */
    protected function getSelectOptions($fieldOptionString)
    {
        $filterOptions = ['' => ' '];
        $fieldOptions = explode("\n", $fieldOptionString);
        foreach ($fieldOptions as $fieldOption) {
            $filterOptions[$fieldOption] = $fieldOption;
        }

        return $filterOptions;
    }
    
     /**
      * @param string $fieldName
      * @return string
      */
    protected function translitFieldName($fieldName)
    {
        // replace non letter or digits by -
        $fieldName = preg_replace('~[^\pL\d]+~u', '-', $fieldName);

        // transliterate
        $fieldName = iconv('utf-8', 'us-ascii//TRANSLIT', $fieldName);

        // remove unwanted characters
        $fieldName = preg_replace('~[^-\w]+~', '', $fieldName);

        // trim
        $fieldName = trim($fieldName, '-');

        // remove duplicate -
        $fieldName = preg_replace('~-+~', '-', $fieldName);

        // lowercase
        $fieldName = strtolower($fieldName);

        if (empty($fieldName)) {
            return 'n-a';
        }

        return $fieldName;
    }
    
    /**
     * @param string $fieldType
     * @return mixed
     */
    protected function getFieldDataType($fieldType)
    {
        switch ($fieldType) {
            case 'select':
                $type = \Magento\Backend\Block\Widget\Grid\Column\Renderer\Select::class;
                break;
            case 'radio':
            case 'checkbox':
                $type = \Magento\Backend\Block\Widget\Grid\Column\Renderer\Options::class;
                break;
            case 'textarea':
                $type = \Magento\Backend\Block\Widget\Grid\Column\Renderer\Longtext::class;
                break;
            default:
                $type = \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text::class;
                break;
        }

        return $type;
    }
    
    /**
     * @param string $className
     * @param string $registerName
     * @param string $paramName
     * @return mixed
     */
    protected function loadModel($className, $registerName, $paramName)
    {
        $model = $this->loadModelByRegister($registerName);
        if (!$model) {
            $model = $this->loadModelByParam($className, $paramName);
        }

        return $model;
    }
    
    /**
     * @param string $registerName
     * @return mixed
     */
    protected function loadModelByRegister($registerName)
    {
        return false;
        if (!empty($registerName)) {
            $model = $this->registry->registry($registerName);
            if ($model !== null && $model->getId()) {
                return $model;
            }
        }
        
        return false;
    }
    
    /**
     * @param string $className
     * @return mixed
     */
    protected function loadModelByParam($className, $paramName)
    {
        if (!empty($paramName)) {
            $paramValue = $this->getRequest()->getParam($paramName);
            $model = $this->autofillFactory->create($className);

            if (!empty($paramValue)) {
                $model->load($paramValue);
            }
            if ($model->getId()) {
                return $model;
            }
        }

        return false;
    }
    
    /**
     * @param string                        $template
     * @param \Magento\Framework\DataObject $dataObject
     * @return string
     */
    protected function getPredefinedValueOutput($template, $dataObject)
    {
        preg_match_all('/%([a-zA-Z_]+)/', $template, $variables);
        
        $pregKeys = [
            'full',
            'dataKey',
        ];
        
        $output = $template;
        
        if (isset($variables[0]) && !empty($variables[0])) {
            $vars = $this->translitPregMatchOutput($variables, $pregKeys);
            foreach ($vars as $var) {
                $regex = '/' . $var['full'] . '/';
                $replacement = $dataObject->getData($var['dataKey']);
                if (!$replacement) {
                    $replacement = '';
                }
                $output = preg_replace($regex, $replacement, $output);
            }
        }
        
        return $output;
    }
    
    /**
     * @param array $output
     * @param array $keys
     * @return array
     */
    protected function translitPregMatchOutput($output, $keys = [])
    {
        $translitted = [];
        foreach ($output as $dataKey => $matches) {
            foreach ($matches as $matchKey => $matchValue) {
                if (!isset($translitted[$matchKey])) {
                    $translitted[$matchKey] = [];
                }
                
                if (!empty($keys)) {
                    $translitted[$matchKey][$keys[$dataKey]] = $matchValue;
                } else {
                    $translitted[$matchKey][$dataKey] = $matchValue;
                }
            }
        }

        return $translitted;
    }
}
