<?php

namespace Etailors\Forms\Helper\Validator;

class AbstractValidator
{
    
    /**
     * @return string
     */
    public function getErrorMessage()
    {
        $c = get_called_class();

        return $c::ERROR_MSG;
    }
}
