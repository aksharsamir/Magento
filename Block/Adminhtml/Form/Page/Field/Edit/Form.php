<?php

namespace Etailors\Forms\Block\Adminhtml\Form\Page\Field\Edit;

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
        $this->setId('grid_field');
        $this->setTitle(__('Field Information'));
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
                    'action' => $this->getUrl(
                        '*/*/save',
                        [
                            'page_id' => $this->getRequest()->getParam('page_id'),
                            'form_id' => $this->getRequest()->getParam('form_id')
                        ]
                    ),
                    'method' => 'post'
                ]
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
