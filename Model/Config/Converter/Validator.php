<?php

namespace Etailors\Forms\Model\Config\Converter;

class Validator implements \Magento\Framework\Config\ConverterInterface
{
    public function convert($source)
    {
        $formValidators = $source->getElementsByTagName('form_validators')->item(0);
        $processedValidators = [];
        $iterator = 0;
        foreach ($formValidators->getElementsByTagName('validator') as $validator) {
			
            foreach ($validator->childNodes as $validatorInfo) {
				if ($validatorInfo->localName !== null) {
					$processedValidators[$iterator][$validatorInfo->localName] = $validatorInfo->textContent;
				}
            }
            $iterator++;
        }

        return [
			'validators' => $processedValidators
		];
    }
}