<?php

namespace Etailors\Forms\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NotFoundException;
use Etailors\Forms\Model\FormFactory;

class FormRouter implements \Magento\Framework\App\RouterInterface 
{
	/**
	 * @var ActionFactory
	 */
	protected $actionFactory;
	
	/**
	 * @var ResponseInterface
	 */
	protected $_response;
	
	/**
	 * @var FormFactory
	 */
	protected $formFactory;
	
	/**
	 * Constructor
	 *
	 * @param ActionFactory $actionFactory
	 * @param ResponseInterface $response
	 * @param FormFactory $formFactory
	 */
	public function __construct(
		ActionFactory $actionFactory,
		ResponseInterface $response,
		FormFactory $formFactory
	) {
		$this->actionFactory = $actionFactory;
		$this->_response = $response;
		$this->formFactory = $formFactory;
	}
	
	/**
	 * @param RequestInterface $request
	 * @return mixed
	 */
	public function match(RequestInterface $request) 
	{
		$identifier = trim($request->getPathInfo(), '/');
		
		/**
		 * ToDo: Make the identifier customizable in settings
		 */
		if(strpos($identifier, 'form/') !== false && (!$request->isDispatched() && !$request->isForwarded())) {  		
			$identifierParts = explode('/', $identifier);
			unset($identifierParts[0]);
			
			// Set defaults
			$formIdentifier = $identifierParts[1];
			$action = 'index';
			unset($identifierParts[1]);

			
			if ($formIdentifier == 'submit' && isset($identifierParts[2])) {
				$formIdentifier = $identifierParts[2];
				$action = 'submit';
				
				unset($identifierParts[2]);
			}

			$form = $this->resolveFormFromIdentifier($formIdentifier);
			
			if ($form !== null) {
				$request->setModuleName('forms') //module name
					->setControllerName('index') //controller name
					->setActionName($action) //action name
					->setParam('form_id', $form->getId()); //custom parameters
				
				if (!$request->isForwarded()) {
					$params = array_values($identifierParts);
					if (count($params) % 2 == 0) {
						for ($i = 0; $i < count($params); $i += 2) {
							$j = $i + 1;
							$request->setParam($params[$i], $params[$j]);
						}
					}
				}

				return $this->actionFactory->create(
				   'Magento\Framework\App\Action\Forward',
				   ['request' => $request]
				);
			}
			else {	
				return;
			}
		}
		return;
	}
	
	/**
	 * @param array $identifierParts
	 * @return null|\Etailors\Forms\Model\Form
	 */
	private function resolveFormFromIdentifier($formCode) 
	{		
		$collection = $this->formFactory->create()->getCollection();
		$collection->addFieldToFilter('form_code', $formCode);
		if ($collection->count() === 1) {
			return $collection->getFirstItem();
		}
		
		return null;
	}
}