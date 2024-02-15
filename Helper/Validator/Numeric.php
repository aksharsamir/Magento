<?php

namespace Etailors\Forms\Helper\Validator;

class Numeric extends AbstractValidator 
{
	
	const ERROR_MSG = 'This field can only contain numbers';
	const REGEX = '/([0-9]+)/';
	
	public function validate($answer) 
	{
		return preg_match(self::REGEX, $answer);
	}
}