<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Field;
 
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
 
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'field_id';
        $this->_blockGroup = 'Etailors_Forms';
        $this->_controller = 'adminhtml_form_page_field';
 
        parent::_construct();
 
		$this->buttonList->update('save', 'label', __('Save Field'));
		$this->buttonList->add(
			'saveandcontinue',
			[
				'label' => __('Save and Continue Edit'),
				'class' => 'save',
				'data_attribute' => [
					'mage-init' => [
						'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
					],
				]
			],
			10
		);
        
		$this->buttonList->add(
			'delete2',
			[
				'label' => __('Delete'),
				'class' => 'delete',
				'onclick' => 'setLocation(\'' . $this->getUrl('*/*/deleteAction', ['id' => $this->getRequest()->getParam('id')]) . '\')',
			],
			10
		);
		
		$this->buttonList->update('back', 'onclick', "setLocation('" . $this->getUrl('*/page/edit', ['id' => $this->getRequest()->getParam('page_id'), 'form_id' => $this->getRequest()->getParam('form_id')]) . "')");
        $this->buttonList->update('delete', 'label', __('Delete Field'));
	}
 
    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('field_grid')->getId()) {
            return __("Edit Field '%1'", $this->escapeHtml($this->_coreRegistry->registry('field_grid')->getTitle()));
        } else {
            return __('New Field');
        }
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
 
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '', 'page_id' => $this->getRequest()->getParam('page_id'), 'form_id' => $this->getRequest()->getParam('form_id')]);
    }
}