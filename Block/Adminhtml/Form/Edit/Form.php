<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Edit;

use Magento\Directory\Model\Config\Source\Country;

/**
 * Adminhtml staff edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_form');
        $this->setTitle(__('Form Information'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}