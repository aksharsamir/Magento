<?php

namespace Etailors\Forms\Block\Form;

use Etailors\Forms\Block\AbstractFormBlock;

class MandatoryFields extends AbstractFormBlock
{
    
    const CACHE_KEY = 'ETAILORS_FORMS_FORM_MANDATORY_FIELDS';
    
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
}
