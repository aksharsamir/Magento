<?php

namespace Etailors\Forms\Helper\Validator;

class None extends AbstractValidator 
{	
	const ERROR_MSG = '';
	
	public function validate($answer) 
	{
		return true;
	}
}