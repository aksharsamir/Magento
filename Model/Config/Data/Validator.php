<?php

namespace Etailors\Forms\Model\Config\Data;

use Magento\Framework\Config\Data as DataConfig;

class Validator extends DataConfig
{
    /**
     * @return null|string
     */
    public function getValidatorClassByName($name)
    {
        $validators = $this->get('validators');
        
        foreach ($validators as $validator) {
            if ($validator['name'] == $name) {
                return $validator['class'];
            }
        }

        return null;
    }
    
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $validators = $this->get('validators');
        $optionArray = [];
        
        foreach ($validators as $validator) {
            $optionArray[] = [
                'label' => $validator['name'],
                'value' => $validator['class']
            ];
        }
        
        return $optionArray;
    }
}
