<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Pages;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Etailors\Forms\Model\Form\PageFactory $pageFactory
     */
    protected $pageFactory;
    
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data            $backendHelper
     * @param \Etailors\Forms\Model\Form\PageFactory  $pageFactory
     * @param \Magento\Framework\Registry             $registry
     * @param array                                   $data
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Etailors\Forms\Model\Form\PageFactory $pageFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->pageFactory = $pageFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('pagesGrid');
        $this->setDefaultSort('sort_order');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('pages_filter');
    }
    
    /**
     * @return string
     */
    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();//get the parent class buttons
        $form = $this->coreRegistry->registry('form_grid');
        if ($form->getId()) {
            $addButton = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
                ->setData([
                'label'     => 'Add',
                'onclick'   => "setLocation('" . $this->getUrl(
                    'forms/page/new',
                    [
                        'form_id' => $this->coreRegistry->registry('form_grid')->getId()
                    ]
                ) . "')",
                'class'   => 'task'
                ])->toHtml();
        } else {
            $addButton = __("Please save the form first"). '<br />';
        }

        return $addButton.$html;
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
        
        $collection = $this->pageFactory->create()->getCollection();
        $collection->addFieldToFilter('form_id', $formId);
        
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
            'pages_title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => ''
            ]
        );

        $this->addColumn(
            'pages_sort_order',
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
        return $this->getUrl('*/page/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/page/edit',
            [
                'id' => $row->getId(),
                'form_id' => $this->getRequest()->getParam('id')
            ]
        );
    }
}
