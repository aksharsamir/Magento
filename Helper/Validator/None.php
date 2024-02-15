<?php

namespace Etailors\Forms\Helper\Validator;

class None extends AbstractValidator
{

    const JS_VALIDATOR = '';
    
    const ERROR_MSG = '';
    
    /**
     * @param mixed $answer
     * @return boolean
     */
    public function validate($answer)
    {
        return true;
    }
}
