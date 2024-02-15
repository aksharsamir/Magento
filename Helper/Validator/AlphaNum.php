<?php

namespace Etailors\Forms\Helper\Validator;

class AlphaNum extends AbstractValidator
{
    const JS_VALIDATOR = 'validate-alphanum';
    
    const ERROR_MSG = 'This field can only contain letters and numbers';
    const REGEX = '/([a-zA-Z0-9]+)/';
    
    /**
     * @param mixed $answer
     * @return boolean
     */
    public function validate($answer)
    {
        return preg_match(self::REGEX, $answer);
    }
}
