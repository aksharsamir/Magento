<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
	/**
	 * Class constructor
	 *
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		
		$this->setId('page_edit_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Edit page'));
	}

	/**
	 * @return $this
	 */
	protected function _beforeToHtml()
	{
		$this->addTab(
			'page_general',
			[
				'label' => __('General'),
				'title' => __('General'),
				'content' => $this->getLayout()->createBlock(
					'Etailors\Forms\Block\Adminhtml\Form\Page\Edit\Tab\General'
				)->toHtml(),
				'active' => true
			]
		);
		
		$this->addTab(
			'page_fields',
			[
				'label' => __('Fields'),
				'title' => __('Fields'),
				'content' => $this->getLayout()->createBlock(
					'Etailors\Forms\Block\Adminhtml\Form\Page\Edit\Tab\Fields'
				)->toHtml(),
				'active' => false
			]
		);
		
		return parent::_beforeToHtml();
	}
}