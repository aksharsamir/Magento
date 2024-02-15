<?php

namespace Etailors\Forms\Model\Config\Converter;

class Template implements \Magento\Framework\Config\ConverterInterface
{

    /**
     * @return array
     */
    public function convert($source)
    {
        $formTemplates = $source->getElementsByTagName('form_templates')->item(0);
        $processedFormTemplates = [];
        $iterator = 0;
        foreach ($formTemplates->getElementsByTagName('template') as $template) {
            foreach ($template->childNodes as $templateInfo) {
                if ($templateInfo->localName !== null) {
                    $processedFormTemplates[$iterator][$templateInfo->localName] = $templateInfo->textContent;
                }
            }
            $iterator++;
        }

        $pageTemplates = $source->getElementsByTagName('page_templates')->item(0);
        $processedPageTemplates = [];
        $iterator = 0;
        foreach ($pageTemplates->getElementsByTagName('template') as $template) {
            foreach ($template->childNodes as $templateInfo) {
                if ($templateInfo->localName !== null) {
                    $processedPageTemplates[$iterator][$templateInfo->localName] = $templateInfo->textContent;
                }
            }
            $iterator++;
        }
        
        $fieldTemplates = $source->getElementsByTagName('field_templates')->item(0);
        $processedFieldTemplates = [];
        $iterator = 0;
        foreach ($fieldTemplates->getElementsByTagName('template') as $template) {
            foreach ($template->childNodes as $templateInfo) {
                if ($templateInfo->localName !== null) {
                    $processedFieldTemplates[$iterator][$templateInfo->localName] = $templateInfo->textContent;
                }
            }
            $iterator++;
        }

        return [
            'form_templates' => $processedFormTemplates,
            'page_templates' => $processedPageTemplates,
            'field_templates' => $processedFieldTemplates,
        ];
    }
}
