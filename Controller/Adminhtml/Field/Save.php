<?php
 
namespace Etailors\Forms\Controller\Adminhtml\Field;
 
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

        $id = $this->getRequest()->getParam('field_id');
        /** @var \Magebuzz\Staff\Model\Grid $model */
        $model = $this->_objectManager->create('Etailors\Forms\Model\Form\Page\Field');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This field no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }
        
        if (!$model->getId()) {
            $model->setCreatedAt(date('Y-m-d H:i:s'));
        }

        $model->setData($params);
        $model->setOptions(($params['options'] == 0 && !empty($params['hidden_value'])) ?
            $params['hidden_value'] : $params['options']);
        $model->setIsRequired((isset($params['is_required'])) ? 1 : 0);
        $model->setContainsEmail((isset($params['contains_email'])) ? 1 : 0);
        $model->setDisplayInOverview((isset($params['display_in_overview'])) ? 1 : 0);
        $model->setUpdatedAt(date('Y-m-d H:i:s'));
        $model->save();
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($this->getRequest()->getParam('back') == 'edit') {
            return $resultRedirect->setPath('*/*/edit', [
                'id' => $model->getId(),
                'page_id' => $this->getRequest()->getParam('page_id'),
                'form_id' => $this->getRequest()->getParam('form_id')
            ]);
        } else {
            return $resultRedirect->setPath('*/page/edit', [
                'id' => $this->getRequest()->getParam('page_id'),
                'form_id' => $this->getRequest()->getParam('form_id')
            ]);
        }
    }
}
