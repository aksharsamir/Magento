<?php

namespace Etailors\Forms\Model\Config\Converter;

class Autofill implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * @return array
     */
    public function convert($source)
    {
        $autofillClasses = $source->getElementsByTagName('autofill_classes')->item(0);
        $processedClasses = [];
        $iterator = 0;
        foreach ($autofillClasses->getElementsByTagName('class') as $autofillClass) {
            foreach ($autofillClass->childNodes as $classInfo) {
                if ($classInfo->localName !== null) {
                    $processedClasses[$iterator][$classInfo->localName] = $classInfo->textContent;
                }
            }
            $iterator++;
        }

        return [
            'autofill_classes' => $processedClasses
        ];
    }
}
