<?php

namespace Etailors\Forms\Helper\Validator;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Etailors\Forms\Helper\Configuration as ConfigurationHelper;

class Recaptcha extends AbstractValidator 
{	
	const ERROR_MSG = 'Recaptcha is not valid';
	
	protected $request;
	
	protected $remoteAddress;
	
	protected $configurationHelper;
	
	public function __construct (
		RequestInterface $request,
		RemoteAddress $remoteAddress,
		ConfigurationHelper $configurationHelper
	) {
		$this->request = $request;
		$this->remoteAddress = $remoteAddress;
		$this->configurationHelper = $configurationHelper;
	}
	
	public function validate($answer) 
	{
		$validResponse = $this->validateResponse($answer);
		return $validResponse;
	}
	
	protected function validateResponse($response) {
		if ($response === null || empty($response)) {
			return false;
		}
		$secretKey = $this->configurationHelper->getSetting('secret_key', 'recaptcha');
		
		$ch = curl_init();
		$post_data = http_build_query([
			'secret' => $secretKey,
			'response' => $response,
			'remoteip' => $this->remoteAddress->getRemoteAddress()
		]);
		
		$opts = ['http' => [
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $post_data
		]];
    
        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
		
		$result = json_decode($response);

		return $result->success;
	}
		
}