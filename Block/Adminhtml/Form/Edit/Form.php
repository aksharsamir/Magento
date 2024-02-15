<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit;

use Magento\Directory\Model\Config\Source\Country;

/**
 * Adminhtml staff edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
	
	/**
	 * @var \Magento\Directory\Model\Config\Source\Country
	 */
	protected $_country;
 
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
        \Magento\Store\Model\System\Store $systemStore,
		Country $country,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
		$this->_country = $country;
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
        $form = $this->_formFactory->create(
			[
				'data' => [
					'id' => 'edit_form',
					'action' => $this->getData('action'),
					'method' => 'post'
				]
			]
		);

		$form->setUseContainer(true);
		$this->setForm($form);

		return parent::_prepareForm();

    }
}