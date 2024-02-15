<?php

namespace Etailors\Forms\Helper;

use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Template as TemplateHelper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
 
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
 
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;
 
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;
    
    /**
     * @var TemplateHelper $templateHelper
     */
    protected $templateHelper;
     
    /**
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder
     * @param TemplateHelper                                     $templateHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        TemplateHelper $templateHelper
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->templateHelper = $templateHelper;
    }
    
    /**
     * Return store
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
    
    /**
     * @param \Etailors\Forms\Model\Form  $form
     * @param \Etailors\Forms\Model\Email $email
     * @return void
     */
    public function sendEmails($form, $email)
    {
        $this->sendEmail('admin', $form, $email);
        if ($form->getUserEmailEnabled() == 1) {
            $this->sendEmail('user', $form, $email);
        }
    }
    
    /**
     * @param string                      $to
     * @param \Etailors\Forms\Model\Form  $form
     * @param \Etailors\Forms\Model\Email $email
     * @return void
     */
    public function sendEmail($to, $form, $email)
    {
        $rawContent = $form->getData($to . '_email_content');
        $processedContent = $this->templateHelper->processRaw($rawContent, $form, $email);

        $rawSubject = $form->getData($to . '_email_subject');
        $processedSubject = $this->templateHelper->processRaw($rawSubject, $form, $email);

        $emailVariables = [
            'content' => $processedContent,
            'subject' => $processedSubject
        ];
        $templateId = 'etailors_forms_email_'.$to;
        
        if ($to === 'user') {
            $senderInfo = ['email' => $form->getUserEmailEmail(), 'name' => $form->getUserEmailName()];
            $receiverInfo = ['email' => $email->getEmail()];
        } else {
            $senderInfo = ['email' => $email->getEmail(), 'name' => 'WebsiteGebruiker'];
            $receiverInfo = ['email' => $form->getAdminEmailEmail(), 'name' => $form->getAdminEmailName()];
        }
        
        $this->inlineTranslation->suspend();
        $this->generateTemplate($templateId, $emailVariables, $receiverInfo, $senderInfo);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
    
    /**
     * @param string $templateId
     * @param array  $variables
     * @param array  $receiver
     * @param array  $sender
     * @return this
     */
    public function generateTemplate($templateId, $variables, $receiver, $sender)
    {
        $template =  $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($variables)
            ->setFrom($sender);
        if (isset($receiver['name'])) {
            $template->addTo($receiver['email'], $receiver['name']);
        } else {
            $template->addTo($receiver['email']);
        }
                
        return $this;
    }
}
