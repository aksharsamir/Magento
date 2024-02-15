<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Adminhtml staff edit form
 */
class Validation extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
	
	protected $request;
	
	protected $validatorConfig;
	
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
		\Etailors\Forms\Model\Config\Data\Validator $validatorConfig,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
		$this->request = $request;
		$this->validatorConfig = $validatorConfig;
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
            $fieldset->addField('field_id', 'hidden', ['name' => 'id', 'value' => $model->getId()]);
        }
 
        $fieldset->addField(
            'is_required',
            'checkbox',
            [
                'label' => __('Is required?'),
                'title' => __('Is required?'),
                'name' => 'is_required',
                'required' => false,
				'checked' => ($model->getIsRequired() == 1) ? true : false,
				'onchange' => 'this.value = this.checked;'
            ]
        );
		
		$fieldset->addField(
            'contains_email',
            'checkbox',
            [
                'label' => __('Container user emailaddress?'),
                'title' => __('Container user emailaddress?'),
                'name' => 'contains_email',
                'required' => false,
				'checked' => ($model->getContainsEmail() == 1) ? true : false,
				'onchange' => 'this.value = this.checked;'
            ]
        );
		
		$fieldset->addField(
            'validation',
            'select',
            [
                'label' => __('Additional validation'),
                'title' => __('Additional validation'),
                'name' => 'validation',
                'required' => false,
				'values' => $this->validatorConfig->toOptionArray()
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