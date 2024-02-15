<?php

namespace Etailors\Forms\Block\Form;

use Etailors\Forms\Block\AbstractFormBlock;
use Etailors\Forms\Helper\Session as SessionHelper;
use Etailors\Forms\Helper\Template as TemplateHelper;
use Etailors\Forms\Helper\Url as UrlHelper;
use Etailors\Forms\Model\EmailFactory;
use Etailors\Forms\Model\FormFactory;
use Magento\Framework\View\Element\Template\Context as TemplateContext;

class Submit extends AbstractFormBlock
{
    
    /**
     * @var EmailFactory
     */
    protected $emailFactory;
    
    /**
     * @var SessionHelper
     */
    protected $sessionHelper;
    
    /**
     * @var TemplateHelper
     */
    protected $templateHelper;
    
    /**
     * @var string
     */
    protected $_template = 'Etailors_Forms::submit.phtml';
    
    /**
     * @param TemplateContext $context
     * @param EmailFactory    $emailFactory
     * @param FormFactory     $formFactory
     * @param UrlHelper       $urlHelper
     * @param SessionHelper   $sessionHelper
     * @param TemplateHelper  $templateHelper
     * @param array           $data
     * @return void
     */
    public function __construct(
        TemplateContext $context,
        EmailFactory $emailFactory,
        FormFactory $formFactory,
        UrlHelper $urlHelper,
        SessionHelper $sessionHelper,
        TemplateHelper $templateHelper,
        array $data = []
    ) {
        $this->emailFactory = $emailFactory;
        $this->sessionHelper = $sessionHelper;
        $this->templateHelper = $templateHelper;
        parent::__construct($context, $formFactory, $urlHelper, $data);
    }
    
    /**
     * @return string
     */
    public function getContent()
    {

        $form = $this->getForm();

        $rawContent = $form->getThankYouPageContent();
        
        $emailId = $this->sessionHelper->getEmailId();
        $email = $this->emailFactory->create()->load($emailId);
                
        $processedContent = $this->templateHelper->processRaw($rawContent, $form, $email);
        
        $this->sessionHelper->unsetValues();
        $this->sessionHelper->unsetValidationResults();
        $this->sessionHelper->unsetEmailId();
        
        return $processedContent;
    }
}
