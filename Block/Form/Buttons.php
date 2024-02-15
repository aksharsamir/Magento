<?php

namespace Etailors\Forms\Block\Form;

use Etailors\Forms\Block\AbstractFormBlock;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Model\FormFactory;

class Buttons extends AbstractFormBlock
{
    
    const CACHE_KEY = 'ETAILORS_FORMS_FORM_BUTTONS';
    
    /**
     * @var string
     */
    protected $_template = 'Etailors_Forms::form/content/buttons.phtml';

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
    public function getPreviousPageUrl()
    {
        return $this->urlHelper->getPreviousPageUrl($this->getForm());
    }
    
    /**
     * @return integer
     */
    public function getPreviousPage()
    {
        return $this->urlHelper->getCurrentPage() - 1;
    }
    
    /**
     * @return integer
     */
    public function getNextPage()
    {
        return $this->urlHelper->getCurrentPage() + 1;
    }
    
    /**
     * @return boolean
     */
    public function displayPreviousButton()
    {
        $form = $this->getForm();
        if ($form->getTreatPagesAsSections()) {
            return false;
        } elseif ($this->urlHelper->getCurrentPage() == 1) {
            return false;
        }

        return true;
    }
    
    /**
     * @return boolean
     */
    public function displaySubmitButton()
    {
        $form = $this->getForm();

        if ($form->getTreatPagesAsSections() == '1') {
            return true;
        } elseif ($this->urlHelper->getCurrentPage() == $form->getPagesCount()) {
            return true;
        }

        return false;
    }
}
