<?php

namespace Etailors\Forms\Model\Config\Data;

use Magento\Framework\Config\Data as DataConfig;

class Template extends DataConfig
{
	public function toOptionArray($objectType) 
	{
		$templates = $this->get($objectType . '_templates');
		$optionArray = [];
		
		foreach ($templates as $template) {
			$optionArray[] = [
				'label' => $template['name'],
				'value' => $template['file']
			];
		}
		
		return $optionArray;
	}
}