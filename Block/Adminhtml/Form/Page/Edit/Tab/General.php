<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class General extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
	
	protected $request;
	
	protected $templateConfig;
 
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
		\Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
		\Etailors\Forms\Model\Config\Data\Template $templateConfig,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
		$this->request = $request;
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

        $this->setId('grid_page');
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
        $model = $this->_coreRegistry->registry('page_grid');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
		$this->setForm($form);
  
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );
	
        if ($model->getId()) {
            $fieldset->addField('page_id', 'hidden', ['name' => 'id', 'value' => $model->getId()]);
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
				'values' => $this->templateConfig->toOptionArray('page')
            ]
        );
		
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