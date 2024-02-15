<?php

namespace Etailors\Forms\Helper;

use Magento\Framework\App\Helper\Context;
use Etailors\Forms\Helper\Validator\ValidatorFactory as ValidatorFactory;
use Etailors\Forms\Model\Validation\ResultFactory as ValidationResultFactory;
use Etailors\Forms\Model\Config\Data\Validator as ValidatorConfig;

class Validator extends \Magento\Framework\App\Helper\AbstractHelper 
{
	protected $validationResultFactory;
	
	protected $validationResult;
	
	protected $validatorConfig;
	
	protected $validatorFactory;
	
	public function __construct(
		Context $context,
		ValidationResultFactory $validationResultFactory,
		ValidatorFactory $validatorFactory,
		ValidatorConfig $validatorConfig
	) {
		$this->validationResultFactory = $validationResultFactory;
		$this->validatorConfig = $validatorConfig;
		$this->validatorFactory = $validatorFactory;
		$this->validationResult = $this->validationResultFactory->create();
		parent::__construct($context);
	}
	
	public function validate($field, $value) 
	{
		$validationResult = $this->validationResultFactory->create();
		$validationResult->setValue($value);
		
		// Special handling for Recaptcha field
		
		if ($field->getType() === 'recaptcha') {
			$validatorClass = \Etailors\Forms\Helper\Validator\Recaptcha::class;
			$validator = $this->validatorFactory->create($validatorClass);
			if (!$validator->validate($value)) {
				$validationResult->setIsValid(false);
				$validationResult->addError($validator->getErrorMessage());
			}
		}
		else {
			if ($field->getIsRequired() && empty($value)) {
				$validationResult->setIsValid(false);
				$validationResult->addError('This field is required');

				return $validationResult;
			}elseif (!empty($value) && !empty($field->getValidation())) {
				$validatorClass = $this->validatorConfig->getValidatorClassByName($field->getValidation());
				$validator = $this->validatorFactory->create($field->getValidation());
				if (!$validator->validate($value)) {
					$validationResult->setIsValid(false);
					$validationResult->addError($validator->getErrorMessage());
				}
			}
		}
		
		return $validationResult;
	}
}