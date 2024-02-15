<?php

namespace Etailors\Forms\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Model\Session;

class AbstractFormBlock extends Template
{
   
    /**
     * @var FormFactory
     */
    protected $formFactory;
    
    /**
     * @var \Etailors\Forms\Model\Form
     */
    protected $form;
    
    /**
     * @param TemplateContext $context
     * @param FormFactory     $formFactory
     * @param UrlHelper       $urlHelper
     * @param array           $data
     */
    public function __construct(
        TemplateContext $context,
        FormFactory $formFactory,
        UrlHelper $urlHelper,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->urlHelper = $urlHelper;
        parent::__construct($context, $data);
    }
    
    /**
     * @return string
     */
    public function canChangeUrl()
    {
        if ($this->getData('is_widget_based') === true) {
            return 'false';
        }
        return 'true';
    }
    
    /**
     * Set the template from form
     * @return void
     */
    public function _prepareLayout()
    {
        if ($this->getPageNum()) {
            $this->urlHelper->setCurrentPage($this->getPageNum());
        } elseif (!$this->getPageNum()) {
            $this->setPageNum($this->urlHelper->getCurrentPage());
        }

        parent::_prepareLayout();
    }
    
    /**
     * @return \Etailors\Forms\Model\Form
     */
    public function getForm()
    {
        if ($this->form === null) {
            if ($this->getRequest()->getParam('widget_form_id', null) !== null) {
                $this->setData('form_id', $this->getRequest()->getParam('widget_form_id'));
            } elseif ($this->getRequest()->getParam('form_id', null) !== null) {
                $this->setData('form_id', $this->getRequest()->getParam('form_id'));
            }

            $this->form = $this->formFactory->create()->load($this->getData('form_id'));
        }
        return $this->form;
    }
    
    /**
     * @return string
     */
    public function getPostUrl()
    {
        return $this->urlHelper->getCurrentPageUrl($this->getForm());
    }
}
