<?php

namespace Etailors\Forms\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

class Url extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    const BASE_SUBMIT_URL = 'submit';
    const BASE_FORM_URL = 'form';
    const FORM_ID_REQUEST_PARAM_KEY = 'form_id';
    const PAGE_NUM_REQUEST_PARAM_KEY = 'page';
    const REFERER_REQUEST_PARAM_KEY = 'referer';
    
    /**
     * @var integer
     */
    protected $currentPage;
    
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var urlInterface
     */
    protected $urlInterface;
    
    /**
     * @param Context               $context
     * @param UrlInterface          $urlInterface
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        Context $context,
        UrlInterface $urlInterface,
        StoreManagerInterface $storeManager
    ) {
        $this->urlInterface = $urlInterface;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    
    /**
     * @param string $relativeUrl
     * @return string
     */
    public function getAbsoluteUrl($relativeUrl)
    {
        $baseWebUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
        return $baseWebUrl . $relativeUrl;
    }
    
    /**
     * @param \Etailors\Forms\Model\Form $form
     * @param boolean                    $keepAdditionalParams
     * @param boolean                    $forceAbsolute
     * @return string
     */
    public function getSubmitUrl($form, $keepAdditionalParams = true, $forceAbsolute = true)
    {
        $referer = $this->_request->getParam('referer', false);
        if ($referer) {
            $refererUrl = base64_decode($referer);
            return $refererUrl;
        }
        
        $urlParts = [];
        $urlParts[] = self::BASE_FORM_URL;
        $urlParts[] = self::BASE_SUBMIT_URL;
        $urlParts[] = $form->getFormCode();
        
        $postParamsKeys = array_keys($this->_request->getPostValue());

        if ($keepAdditionalParams) {
            foreach ($this->_request->getParams() as $paramKey => $paramValue) {
                if (!in_array($paramKey, $postParamsKeys) && !is_array($paramValue) && !empty($paramValue)) {
                    if ($paramKey !== self::FORM_ID_REQUEST_PARAM_KEY &&
                            $paramKey !== self::PAGE_NUM_REQUEST_PARAM_KEY
                    ) {
                        $urlParts[] = $paramKey;
                        $urlParts[] = $paramValue;
                    }
                }
            }
        }
        
        $relativeUrl = implode('/', $urlParts) . '/';
        
        if ($forceAbsolute) {
            return $this->getAbsoluteUrl($relativeUrl);
        }
        
        return $relativeUrl;
    }
    
    /**
     * @param \Etailors\Forms\Model\Form $form
     * @param boolean                    $keepAdditionalParams
     * @param boolean                    $addReferer
     * @param boolean                    $forceAbsolute
     * @return string
     */
    public function getCurrentPageUrl($form, $keepAdditionalParams = true, $addReferer = false, $forceAbsolute = true)
    {
        $urlParts = [];
        $urlParts[] = self::BASE_FORM_URL;
        $urlParts[] = $form->getFormCode();
        
        $postParamsKeys = array_keys($this->_request->getPostValue());
        
        if ($keepAdditionalParams) {
            foreach ($this->_request->getParams() as $paramKey => $paramValue) {
                if (!in_array($paramKey, $postParamsKeys) && !is_array($paramValue) && !empty($paramValue)) {
                    if ($paramKey !== self::FORM_ID_REQUEST_PARAM_KEY &&
                            $paramKey !== self::PAGE_NUM_REQUEST_PARAM_KEY
                    ) {
                        $urlParts[] = $paramKey;
                        $urlParts[] = $paramValue;
                    }
                }
            }
        }
        
        $currentPage = $this->getCurrentPage();
        
        $urlParts[] = self::PAGE_NUM_REQUEST_PARAM_KEY;
        $urlParts[] = $currentPage;
        
        if ($addReferer && !empty($this->urlInterface->getCurrentUrl())) {
            $urlParts[] = self::REFERER_REQUEST_PARAM_KEY;
            $urlParts[] = base64_encode($this->urlInterface->getCurrentUrl());
        }

        $relativeUrl = implode('/', $urlParts);
        $relativeUrl .= '/';
        
        if ($forceAbsolute) {
            return $this->getAbsoluteUrl($relativeUrl);
        }
        
        return $relativeUrl;
    }
    
    /**
     * @param \Etailors\Forms\Model\Form $form
     * @param boolean                    $keepAdditionalParams
     * @param boolean                    $forceAbsolute
     * @return string
     */
    public function getNextPageUrl($form, $keepAdditionalParams = true, $forceAbsolute = true)
    {
        $urlParts = [];
        $urlParts[] = self::BASE_FORM_URL;
        $urlParts[] = $form->getFormCode();
        
        $postParamsKeys = array_keys($this->_request->getPostValue());
        
        if ($keepAdditionalParams) {
            foreach ($this->_request->getParams() as $paramKey => $paramValue) {
                if (!in_array($paramKey, $postParamsKeys) && !is_array($paramValue) && !empty($paramValue)) {
                    if ($paramKey !== self::FORM_ID_REQUEST_PARAM_KEY &&
                            $paramKey !== self::PAGE_NUM_REQUEST_PARAM_KEY
                    ) {
                        $urlParts[] = $paramKey;
                        $urlParts[] = $paramValue;
                    }
                }
            }
        }
        
        $currentPage = $this->getCurrentPage();
        $nextPage = $currentPage + 1;
        
        $urlParts[] = self::PAGE_NUM_REQUEST_PARAM_KEY;
        $urlParts[] = $nextPage;

        $relativeUrl = implode('/', $urlParts);
        $relativeUrl .= '/';
        
        if ($forceAbsolute) {
            return $this->getAbsoluteUrl($relativeUrl);
        }
        
        return $relativeUrl;
    }
    
    /**
     * @param \Etailors\Forms\Model\Form $form
     * @param boolean                    $keepAdditionalParams
     * @param boolean                    $forceAbsolute
     * @return string
     */
    public function getPreviousPageUrl($form, $keepAdditionalParams = true, $forceAbsolute = true)
    {
        $urlParts = [];
        $urlParts[] = self::BASE_FORM_URL;
        $urlParts[] = $form->getFormCode();
        
        $postParamsKeys = array_keys($this->_request->getPostValue());
        
        if ($keepAdditionalParams) {
            foreach ($this->_request->getParams() as $paramKey => $paramValue) {
                if (!in_array($paramKey, $postParamsKeys) && !is_array($paramValue) && !empty($paramValue)) {
                    if ($paramKey !== self::FORM_ID_REQUEST_PARAM_KEY &&
                            $paramKey !== self::PAGE_NUM_REQUEST_PARAM_KEY
                    ) {
                        $urlParts[] = $paramKey;
                        $urlParts[] = $paramValue;
                    }
                }
            }
        }
        
        $currentPage = $this->getCurrentPage();
        $previousPage = $currentPage - 1;
        
        $urlParts[] = self::PAGE_NUM_REQUEST_PARAM_KEY;
        $urlParts[] = $previousPage;
        
        $relativeUrl = implode('/', $urlParts);
        $relativeUrl .= '/';
        
        if ($forceAbsolute) {
            return $this->getAbsoluteUrl($relativeUrl);
        }
        
        return $relativeUrl;
    }
    
    /**
     * @return integer
     */
    public function getCurrentPage()
    {
        if (!$this->currentPage) {
            $pageNum = (int)$this->_request->getParam(self::PAGE_NUM_REQUEST_PARAM_KEY);
            if ($pageNum == 0) {
                $pageNum = 1;
            }
            $this->currentPage = $pageNum;
        }
        return $this->currentPage;
    }
    
    /**
     * @param integer $pageNum
     * @return void
     */
    public function setCurrentPage($pageNum)
    {
        $this->currentPage = $pageNum;
    }
}
