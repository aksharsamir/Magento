<?php

namespace Etailors\Forms\Block\Form;

use Etailors\Forms\Block\AbstractFormBlock;

class Fields extends AbstractFormBlock
{
    
    const CACHE_KEY = 'ETAILORS_FORMS_FORM_FIELDS';
    
    /**
     * @var string
     */
    protected $_template = 'Etailors_Forms::form/content/fields.phtml';
    
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
    public function getContent()
    {
        $form = $this->getForm();

        if ($form->getTreatPagesAsSections()) {
            $html = '';
            foreach ($form->getPages() as $page) {
                $pageBlock = $this->addChild(
                    'etailors_forms_form_page_'.$page->getId(),
                    \Etailors\Forms\Block\Form\Page::class,
                    ['page' => $page]
                );
    
                $html .= $pageBlock->toHtml();
            }
        } else {
            $page = $form->getPageByNum($this->getPageNum());

            $pageBlock = $this->addChild(
                'etailors_forms_form_page_'.$page->getId(),
                \Etailors\Forms\Block\Form\Page::class,
                ['page' => $page]
            );
                
            $html = $pageBlock->toHtml();
        }

        return $html;
    }
}
