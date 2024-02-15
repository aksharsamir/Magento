<?php

namespace Etailors\Forms\Block\Widget;

use Etailors\Forms\Block\AbstractFormBlock;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Url as UrlHelper;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\View\Result\PageFactory;

class Form extends AbstractFormBlock implements BlockInterface
{
    
    const CACHE_TAG = 'etailors_forms_widget';
    
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    
    /**
     * @var string
     */
    protected $_template = 'Etailors_Forms::widget.phtml';
    
    /**
     * @param TemplateContext $context
     * @param FormFactory     $formFactory
     * @param UrlHelper       $urlHelper
     * @param PageFactory     $resultPageFactory
     * @param array           $data
     */
    public function __construct(
        TemplateContext $context,
        FormFactory $formFactory,
        UrlHelper $urlHelper,
        PageFactory $resultPageFactory,
        array $data = []
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $formFactory, $urlHelper, $data);
    }
    
    /**
     * Keep cache key unique for each form
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            self::CACHE_KEY,
            $this->_storeManager->getStore()->getId(),
            $this->getForm()->getId(),
            $this->getRequest()->getParam('page', 1)
        ];
    }
    
    /**
     * @return string
     */
    public function canChangeUrl()
    {
        return 'false';
    }
    
    /**
     * @return string
     */
    public function getFormBlockHtml()
    {
        $formId = $this->getData('form_id');
        $this->getRequest()->setParam('widget_form_id', $formId);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->addHandle('forms_index_index');
        
        $block = $resultPage->getlayout()->getBlock('etailors_forms_form')
            ->setData('is_widget_based', true)
            ->setData('form_id', $formId);
        
        return $block->toHtml();
    }
}
