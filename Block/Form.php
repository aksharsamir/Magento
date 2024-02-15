<?php

namespace Etailors\Forms\Block;

use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Model\Session;

class Form extends AbstractFormBlock
{

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
     * Set the template from form
     * @return void
     */
    public function _prepareLayout()
    {
        $form = $this->getForm();
        $formTemplate = $form->getTemplate();
        if (strpos($formTemplate, '::') === false) {
            $formTemplate = 'Etailors_Forms::' . $formTemplate;
        }
        $this->setTemplate($formTemplate);
        
        if (!$this->getPageNum()) {
            $this->setPageNum($this->urlHelper->getCurrentPage());
        }

        parent::_prepareLayout();
    }
}
