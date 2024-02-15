<?php

namespace Etailors\Forms\Helper\Validator;

class Numeric extends AbstractValidator
{
    const JS_VALIDATOR = 'validate-digit';
    
    const ERROR_MSG = 'This field can only contain numbers';
    const REGEX = '/([0-9]+)/';
    
    /**
     * @param mixed $answer
     * @return boolean
     */
    public function validate($answer)
    {
        return preg_match(self::REGEX, $answer);
    }
}
