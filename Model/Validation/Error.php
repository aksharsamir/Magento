<?php

namespace Etailors\Forms\Model\Validation;

class Error extends \Magento\Framework\DataObject
{

    /**
     * @var string
     */
    protected $message;
    
    /**
     * @param string $message
     * @return void
     */
    public function __construct($message = '')
    {
        $this->message = $message;
        parent::__construct([]);
    }
    
    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @param string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
