<?php
 
namespace Etailors\Forms\Controller\Adminhtml\Field;
 
class DeleteAction extends \Magento\Backend\App\Action
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
        $model = $this->_objectManager->create('Etailors\Forms\Model\Form\Page\Field');
        
        /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($id) {
            $model->load($id);
            $pageId = $model->getPageId();
            if (!$model->getId()) {
                $this->messageManager->addError(__('This field no longer exists.'));
                return $resultRedirect->setPath('*/page/edit', ['id' => $pageId]);
            }
            
            try {
                $model->delete();
                $this->messageManager->addSuccess(__('The field has been deleted.'));
                return $resultRedirect->setPath('*/page/edit', ['id' => $pageId]);
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong trying to delete the field.'));
                return $resultRedirect->setPath('*/page/edit', ['id' => $pageId]);
            }
        }
        
        $this->messageManager->addError(__('Something went wrong trying to delete the field.'));
        
        return $resultRedirect->setPath('*/editor/index');
    }
}
