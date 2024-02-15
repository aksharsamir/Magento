<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class ThankYouPage extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
	
	protected $templateConfig;
	
	/**
	 * @var \Magento\Cms\Model\Wysiwyg\Config
	 */
	protected $_wysiwygConfig;
	
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
		\Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
		\Etailors\Forms\Model\Config\Data\Template $templateConfig,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
		$this->_wysiwygConfig = $wysiwygConfig;
		$this->templateConfig = $templateConfig;
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
            ['legend' => __('Thank you page'), 'class' => 'fieldset-wide']
        );
		
		$fieldset->addField(
            'thank_you_page_content',
            'editor',
            [
                'label' => __('Content'),
                'title' => __('Content'),
                'name' => 'thank_you_page_content',
                'required' => true,
				'class' => 'required',
				'wysiwyg' => true,
				'rows' => '15',
				'cols' => '30',
				'config' => $this->_wysiwygConfig->getConfig(),
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
		return __('Thank you page');
	}

	/**
	 * Return Tab title
	 *
	 * @return string
	 * @api
	 */
	public function getTabTitle()
	{
		return __('Thank you page');
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