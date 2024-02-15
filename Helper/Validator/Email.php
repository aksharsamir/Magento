<?php

namespace Etailors\Forms\Helper\Validator;

class Email extends AbstractValidator 
{
	const ERROR_MSG = 'This is not a valid emailaddress';
	
	public function validate($answer) 
	{
		return filter_var($answer, FILTER_VALIDATE_EMAIL);
	}
}