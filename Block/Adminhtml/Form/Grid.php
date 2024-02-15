<?php

namespace Etailors\Forms\Block\Adminhtml\Form;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended 
{	
    /**
     * @var \Etailors\Forms\Model\FormFactory $formFactory
     */
    protected $formFactory;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
		\Etailors\Forms\Model\FormFactory $formFactory,
        \Magento\Store\Model\StoreFactory $storeFactory,
        array $data = []
    ) {
		$this->formFactory = $formFactory;
		$this->storeFactory = $storeFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('formsGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('form_filter');
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
		$collection = $this->formFactory->create()->getCollection();
		$pagesTable = $collection->getResource()->getTable('etailors_forms_page');        
		$collection->getSelect()->joinLeft(
			['pages' => $pagesTable],
			'pages.form_id = main_table.form_id',
			['num_pages' => 'COUNT(pages.page_id)']
		);
		$collection->getSelect()->group('main_table.form_id');
		
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
            'title',
            [
                'header' => __('Title'),
                'index' => 'title',
                'class' => ''
            ]
        );
		
		$this->addColumn(
            'form_code',
            [
                'header' => __('Key'),
                'index' => 'form_code',
                'class' => ''
            ]
        );
        
		$this->addColumn(
            'pages',
            [
                'header' => __('# pages'),
                'index' => 'num_pages',
                'class' => '',
				'resizeEnabled' => false,
				'resizeDefaultWidth' => 75,
				'width'  => 60
            ]
        );

        return parent::_prepareColumns();
    }

    
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['id' => $row->getId()]
        );
    }
}