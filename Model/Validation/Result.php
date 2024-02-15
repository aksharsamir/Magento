<?php

namespace Etailors\Forms\Model\Validation;

use Etailors\Forms\Model\Validation\Error as ValidationError;

class Result extends \Magento\Framework\DataObject
{
    
    /**
     * @var boolean
     */
    protected $isValid = true;
    
    /**
     * @var array
     */
    protected $errors = [];
    
    /**
     * @var string
     */
    protected $value = '';
    
    /**
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->isValid;
    }
    
    /**
     * @param boolean $valid
     * @return void
     */
    public function setIsValid($valid)
    {
        $this->isValid = $valid;
    }
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
        
    /**
     * @param string|ValidationError $error
     * @return void
     */
    public function addError($error)
    {
        if (is_string($error)) {
            $error = new ValidationError($error);
        }
        $this->errors[] = $error;
    }
    
    /**
     * @param array $errors
     * @return void
     */
    public function setErrors($errors)
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }
    
    /**
     * @param string $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
