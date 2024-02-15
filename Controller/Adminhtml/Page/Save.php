<?php
 
namespace Etailors\Forms\Controller\Adminhtml\Page;
 
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Model\View\Result\Redirect
     */
    protected $resultRedirectFactory;
 
    /**
     * @param \Magento\Backend\App\Action\Context                $context
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }
    
    /**
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Etailors_Forms::Forms_Config');
    }
 
    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $id = $this->getRequest()->getParam('id');

        /** @var \Magebuzz\Staff\Model\Grid $model */
        $model = $this->_objectManager->create('Etailors\Forms\Model\Form\Page');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This page no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }

        if (!$model->getId()) {
            $model->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $model->setUpdatedAt(date('Y-m-d H:i:s'));
        $model->setTitle($params['title']);
        $model->setFormId($params['form_id']);
        $model->setSortOrder($params['sort_order']);
        $model->setTemplate($params['template']);
        $model->save();
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($this->getRequest()->getParam('back') == 'edit') {
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), 'form_id' => $model->getFormId()]);
        } else {
            return $resultRedirect->setPath('*/editor/edit', ['id' => $model->getFormId()]);
        }
    }
}
