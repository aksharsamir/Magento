<?php

namespace Etailors\Forms\Model\Validation;

class Error extends \Magento\Framework\DataObject 
{
	protected $message;
	
    public function __construct($message = '')
    {
        $this->message = $message;
		parent::__construct([]);
    }
	
	public function getMessage() 
	{
		return $this->message;
	}
	
	public function setMessage($message) 
	{
		return $this->message = $message;
	}
}