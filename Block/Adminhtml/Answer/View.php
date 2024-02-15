<?php

namespace Etailors\Forms\Block\Adminhtml\Answer;
 
class View extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;
 
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'email_id';
        $this->_blockGroup = 'Etailors_Forms';
        $this->_controller = 'adminhtml_answer';
 
        parent::_construct();
 
        $this->buttonList->remove('save');
        $this->buttonList->remove('delete');
        $this->buttonList->remove('reset');
        
        $this->buttonList->update(
            'back',
            'onclick',
            "setLocation('" . $this->getUrl('*/editor/edit', [
                'id' => $this->getRequest()->getParam('form_id'),
                'active_tab' => 'emails'
            ]) . "')"
        );
 
        $this->buttonList->update('back', 'label', __('Back'));
    }
 
    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->coreRegistry->registry('answer_grid')->getId()) {
            return __("View Answer from '%1'", $this->escapeHtml(
                $this->coreRegistry->registry('answer_grid')->getEmail()
            ));
        }
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return boolean
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
        return null;
    }
}
