<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Edit\Tab\Fields;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory
     */
    protected $fieldFactory;
    
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context      $context
     * @param \Magento\Backend\Helper\Data                 $backendHelper
     * @param \Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory
     * @param \Magento\Framework\Registry                  $registry
     * @param array                                        $data
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Etailors\Forms\Model\Form\Page\FieldFactory $fieldFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->fieldFactory = $fieldFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('fieldGrid');
        $this->setDefaultSort('field_sort_order');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('fields_filter');
    }
    
    /**
     * @return string
     */
    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml(); //get the parent class buttons

        $page = $this->coreRegistry->registry('page_grid');
        if ($page->getId()) {
            $addButton = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
                ->setData([
                'label'     => 'Add',
                'onclick'   => "setLocation('" . $this->getUrl('forms/field/new', [
                        'form_id' => $this->getRequest()->getParam('form_id'),
                        'page_id' => $this->coreRegistry->registry('page_grid')->getId()
                ]) . "')",
                'class'   => 'task'
                ])->toHtml();
        } else {
            $addButton = __("Please save the page first"). '<br />';
        }

        return $addButton.$html;
    }

    /**
     * @return Store
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
        $pageId = $this->getRequest()->getParam('id');
        
        $collection = $this->fieldFactory->create()->getCollection();
        $collection->addFieldToFilter('page_id', $pageId);
        
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'field_title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => ''
            ]
        );

        $this->addColumn(
            'field_type',
            [
                'header' => __('Type'),
                'index' => 'type',
                'class' => ''
            ]
        );
        
        $this->addColumn(
            'field_sort_order',
            [
                'header' => __('Sorting'),
                'index' => 'sort_order',
                'class' => ''
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/field/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/field/edit',
            [
                'id' => $row->getId(),
                'page_id' => $this->getRequest()->getParam('id'),
                'form_id' => $this->getRequest()->getParam('form_id')
            ]
        );
    }
}
