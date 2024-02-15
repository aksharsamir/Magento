<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab;

use Magento\Backend\Block\Widget\Grid\Container;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class Answers extends Container implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $data);
    }
 
    protected function _construct()
	{
		$this->_controller = 'adminhtml_form_edit_tab_answers';
		$this->_blockGroup = 'Etailors_Forms';
		$this->_headerText = __('Answers');
		
		$this->_addNewButton();
		
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
	
	/**
	 * Return Tab label
	 *
	 * @return string
	 * @api
	 */
	public function getTabLabel()
	{
		return __('Answers');
	}

	/**
	 * Return Tab title
	 *
	 * @return string
	 * @api
	 */
	public function getTabTitle()
	{
		return __('Answers');
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