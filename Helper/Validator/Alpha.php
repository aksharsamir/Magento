<?php

namespace Etailors\Forms\Helper\Validator;

class Alpha extends AbstractValidator 
{
	
	const ERROR_MSG = 'This field can only contain letters';
	const REGEX = '/([a-zA-Z]+)/';
	
	public function validate($answer) 
	{
		return preg_match(self::REGEX, $answer);
	}
}