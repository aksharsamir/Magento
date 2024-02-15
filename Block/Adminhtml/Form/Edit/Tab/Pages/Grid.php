<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Pages;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended 
{
    /**
     * @var \Etailors\Forms\Model\Form\PageFactory $pageFactory
     */
    protected $pageFactory;
	
	protected $_coreRegistry;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
		\Etailors\Forms\Model\Form\PageFactory $pageFactory,
        \Magento\Store\Model\StoreFactory $storeFactory,
		\Magento\Framework\Registry $registry,
        array $data = []
    ) {
		$this->pageFactory = $pageFactory;
		$this->_coreRegistry = $registry;
		$this->storeFactory = $storeFactory;
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
	
	public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();//get the parent class buttons
		$form = $this->_coreRegistry->registry('form_grid');
		if ($form->getId()) {
			$addButton = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
				->setData(array(
				'label'     => 'Add',
				'onclick'   => "setLocation('" . $this->getUrl('forms/page/new', ['form_id' => $this->_coreRegistry->registry('form_grid')->getId()]) . "')",
				'class'   => 'task'
			))->toHtml();
		}
		else {
			$addButton = __("Please save the form first"). '<br />';
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
		$formId = $this->getRequest()->getParam('id');
		
        $collection = $this->pageFactory->create()->getCollection();
		$collection->addFieldToFilter('form_id', $formId);
		
		$this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
       
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