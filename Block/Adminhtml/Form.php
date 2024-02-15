<?php

namespace Etailors\Forms\Block\Adminhtml;

class Form extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var \Magento\Catalog\Model\Product\TypeFactory
     */
    protected $_typeFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Catalog\Model\Product\TypeFactory $typeFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Catalog\Model\Product\TypeFactory $typeFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->_typeFactory = $typeFactory;

        parent::__construct($context, $data);
    }
	
	protected function _construct()
	{
		$this->_controller = 'adminhtml_form';
		$this->_blockGroup = 'Etailors_Forms';
        $this->_headerText = __('Forms');
        
		parent::_construct();
	}

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    

}