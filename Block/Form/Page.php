<?php

namespace Etailors\Forms\Block\Form;

use Etailors\Forms\Block\AbstractFormBlock;

class Page extends AbstractFormBlock
{
    
    const CACHE_KEY = 'ETAILORS_FORMS_PAGE';
    
    /**
     * Keep cache key unique for each form
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            self::CACHE_KEY,
            $this->_storeManager->getStore()->getId(),
            $this->getPage()->getId()
        ];
    }
    
    /**
     * Set the template from form
     * @return void
     */
    public function _prepareLayout()
    {
        $pageTemplate = $this->getData('page')->getTemplate();
        if (strpos($pageTemplate, '::') === false) {
            $pageTemplate = 'Etailors_Forms::' . $pageTemplate;
        }
        $this->setTemplate($pageTemplate);

        parent::_prepareLayout();
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        $page = $this->getPage();

        $html = '';
        foreach ($page->getFields() as $field) {
            $fieldBlock = $this->addChild(
                'etailors_forms_form_field_'.$field->getId(),
                \Etailors\Forms\Block\Form\Field::class,
                ['field' => $field]
            );
                                
            $html .= $fieldBlock->toHtml();
        }
        
        return $html;
    }
}
