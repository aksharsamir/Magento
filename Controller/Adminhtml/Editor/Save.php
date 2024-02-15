<?php
 
namespace Etailors\Forms\Controller\Adminhtml\Editor;
 
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
        $model = $this->_objectManager->create('Etailors\Forms\Model\Form');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This form no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }

        if (!$model->getId()) {
            $model->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $model->setUpdatedAt(date('Y-m-d H:i:s'));
        
        /** General **/
        $model->setTitle($params['title']);
        $model->setFormCode($params['form_code']);
        $model->setTemplate($params['template']);
        $model->setTreatPagesAsSections((isset($params['treat_pages_as_sections'])) ? 1 : 0);
        
        /** Admin Email **/
        $model->setAdminEmailEmail($params['admin_email_email']);
        $model->setAdminEmailName($params['admin_email_name']);
        $model->setAdminEmailSubject($params['admin_email_subject']);
        $model->setAdminEmailContent($params['admin_email_content']);
        
        /** User email **/
        $model->setUserEmailEnabled((isset($params['user_email_enabled'])) ? 1 : 0);
        $model->setUserEmailEmail($params['user_email_email']);
        $model->setUserEmailName($params['user_email_name']);
        $model->setUserEmailSubject($params['user_email_subject']);
        $model->setUserEmailContent($params['user_email_content']);
        
        /** Thank you page **/
        $model->setThankYouPageContent($params['thank_you_page_content']);
        
        $model->save();
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($this->getRequest()->getParam('back') == 'edit') {
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } else {
            return $resultRedirect->setPath('*/*/index');
        }
    }
}
