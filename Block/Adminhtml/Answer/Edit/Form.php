<?php

namespace Etailors\Forms\Block\Adminhtml\Answer\Edit;

use Magento\Directory\Model\Config\Source\Country;

/**
 * Adminhtml staff edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    protected $_status;
	
	protected $timezone;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
		Country $country,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
		$this->timezone = $timezone;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_anwer');
        $this->setTitle(__('Email Information'));
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
					'action' => '#',
					'method' => 'post'
				]
			]
		);
		
		$model = $this->_coreRegistry->registry('answer_grid');
		
		$form->setUseContainer(true);
		$this->setForm($form);
		
		$fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Answer'), 'class' => 'fieldset-wide']
        );
		
		$fieldset->addType(
            'unescaped_label',
            '\Etailors\Forms\Block\Adminhtml\Answer\Edit\Renderer\UnescapedLabel'
        );
        
        $fieldset->addField(
            'created_at',
            'label',
            [
                'label' => __('Send on'),
                'title' => __('Send on'),
                'name' => 'created_at',
				'class' => 'admin__field-label',
				'value' => $this->timezone->date($model->getCreatedAt())
						->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT),
            ]
        );
		
		foreach ($model->getAnswers() as $answer) {
			if ($answer->getField()->getType() == 'check') {
				$values = explode(',', $answer->getAnswer());
				foreach ($values as $key => $value) {
					$values[$key] = trim($value);
				}
				$value = implode("\n",$values);
				
			}
			else {
				$value = $answer->getAnswer();
			}
			$fieldset->addField(
				$this->translitFieldName($answer->getField()->getTitle()),
				'unescaped_label',
				[
					'label' => __($answer->getField()->getTitle()),
					'title' => __($answer->getField()->getTitle()),
					'name' => $this->translitFieldName($answer->getField()->getTitle()),
					'value' => nl2br($value),
					'class' => 'admin__field-label',
				]
			);
		}

		return parent::_prepareForm();

    }
	
	protected function translitFieldName($fieldName) 
	{
		// replace non letter or digits by -
		$fieldName = preg_replace('~[^\pL\d]+~u', '-', $fieldName);

		// transliterate
		$fieldName = iconv('utf-8', 'us-ascii//TRANSLIT', $fieldName);

		// remove unwanted characters
		$fieldName = preg_replace('~[^-\w]+~', '', $fieldName);

		// trim
		$fieldName = trim($fieldName, '-');

		// remove duplicate -
		$fieldName = preg_replace('~-+~', '-', $fieldName);

		// lowercase
		$fieldName = strtolower($fieldName);

		if (empty($fieldName)) {
			return 'n-a';
		}

		return $fieldName;
	}
}