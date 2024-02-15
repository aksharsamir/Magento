<?php

namespace Etailors\Forms\Helper;

use Etailors\Forms\Model\FormFactory;
use Etailors\Forms\Helper\Template as TemplateHelper;

class Email extends \Magento\Framework\App\Helper\AbstractHelper 
{
	/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
 
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
 
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;
 
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
	
	protected $templateHelper;
     
    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
    * @param Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		TemplateHelper $templateHelper
		
    ) {
        $this->_scopeConfig = $context;
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder; 
		$this->templateHelper = $templateHelper;
    }
	
	/**
     * Return store 
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore();
    }
	
    public function sendEmails($form, $email) 
    {
		$this->sendEmail('admin', $form, $email);
		if ($form->getUserEmailEnabled() == 1) {
			$this->sendEmail('user', $form, $email);
		}
	}
	
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
		}
		else {
			$senderInfo = ['email' => $email->getEmail(), 'name' => 'WebsiteGebruiker'];
			$receiverInfo = ['email' => $form->getAdminEmailEmail(), 'name' => $form->getAdminEmailName()];
		}
		
		$this->inlineTranslation->suspend();
		$this->generateTemplate($templateId, $emailVariables, $receiverInfo, $senderInfo);
		$transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();        
        $this->inlineTranslation->resume();
	}
	
	public function generateTemplate($templateId, $variables, $receiver, $sender)
    {
        $template =  $this->_transportBuilder->setTemplateIdentifier($templateId)
			->setTemplateOptions(
				[
					'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
					'store' => $this->_storeManager->getStore()->getId(),
				]
			)
			->setTemplateVars($variables)
			->setFrom($sender);
			if (isset($receiver['name'])) {
				$template->addTo($receiver['email'],$receiver['name']);
			}
			else {
				$template->addTo($receiver['email']);
			}
                
        return $this;        
    }
}