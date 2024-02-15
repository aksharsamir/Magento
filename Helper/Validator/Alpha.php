<?php

namespace Etailors\Forms\Helper\Validator;

class Alpha extends AbstractValidator
{
    
    const JS_VALIDATOR = 'validate-alpha';
    
    const ERROR_MSG = 'This field can only contain letters';
    const REGEX = '/([a-zA-Z]+)/';
    
    /**
     * @param mixed $answer
     * @return boolean
     */
    public function validate($answer)
    {
        return preg_match(self::REGEX, $answer);
    }
}
