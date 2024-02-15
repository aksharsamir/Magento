<?php

namespace Etailors\Forms\Model\Validation;

use Etailors\Forms\Model\Validation\Error as ValidationError;

class Result extends \Magento\Framework\DataObject 
{
	protected $isValid = true;
	
	protected $errors = [];
	
	protected $value = '';
	
	public function getIsValid() {
		return $this->isValid;
	}
	
	public function setIsValid($valid) 
	{
		return $this->isValid = $valid;
	}
	
	public function getErrors() 
	{
		return $this->errors;
	}
	
	public function addError($error) 
	{
		if (is_string($error)) {
			$error = new ValidationError($error);
		}
		$this->errors[] = $error;
	}
	
	public function setErrors($errors) 
	{
		foreach ($errors as $error) {
			$this->addError($error);
		}
	}

	public function setValue($value) 
	{
		$this->value = $value;
	}
	
	public function getValue() 
	{
		return $this->value;
	}
	
}