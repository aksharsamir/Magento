<?php

namespace Etailors\Forms\Controller\Adminhtml\Answer;

class Grid extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

    /**
     * Product grid for AJAX request
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $layout = $this->_view->getLayout();
        $block = $layout->createBlock(\Etailors\Forms\Block\Adminhtml\Form\Edit\Tab\Answers\Grid::class);
        $this->getResponse()->appendBody($block->toHtml());
    }
}
