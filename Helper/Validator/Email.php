<?php

namespace Etailors\Forms\Helper\Validator;

class Email extends AbstractValidator
{
    const JS_VALIDATOR = 'validate-email';
    
    const ERROR_MSG = 'This is not a valid emailaddress';
    
    /**
     * @param mixed $answer
     * @return boolean
     */
    public function validate($answer)
    {
        return filter_var($answer, FILTER_VALIDATE_EMAIL);
    }
}
