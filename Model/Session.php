<?php
namespace Etailors\Forms\Model;

class Session extends \Magento\Framework\Session\SessionManager
{

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;
   
    /**
     * @param \Magento\Framework\App\Request\Http                    $request
     * @param \Magento\Framework\Session\SidResolverInterface        $sidResolver
     * @param \Magento\Framework\Session\Config\ConfigInterface      $sessionConfig
     * @param \Magento\Framework\Session\SaveHandlerInterface        $saveHandler
     * @param \Magento\Framework\Session\ValidatorInterface          $validator
     * @param \Magento\Framework\Session\StorageInterface            $storage
     * @param \Magento\Framework\Stdlib\CookieManagerInterface       $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param \Magento\Framework\App\Http\Context                    $httpContext
     * @param \Magento\Framework\App\State                           $appState
     * @param \Magento\Framework\Event\ManagerInterface              $eventManager
     * @param \Magento\Framework\App\Response\Http                   $response
     * @return void
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\Session\Config\ConfigInterface $sessionConfig,
        \Magento\Framework\Session\SaveHandlerInterface $saveHandler,
        \Magento\Framework\Session\ValidatorInterface $validator,
        \Magento\Framework\Session\StorageInterface $storage,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Response\Http $response
    ) {

        $this->eventManager = $eventManager;
 
        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState
        );
        $this->response = $response;
        $this->eventManager->dispatch('etailors_forms_session_init', ['etailors_forms' => $this]);
    }
}
