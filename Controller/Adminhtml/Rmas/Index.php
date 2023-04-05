<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{

    public $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    // @codingStandardsIgnoreStart
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Softnoesis_Rma::rmalist');
    }
    // @codingStandardsIgnoreEnd
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::rmalist');
        $resultPage->addBreadcrumb(__('Rma'), __('Rma'));
        $resultPage->addBreadcrumb(__('Manage RMA list'), __('Manage RMA List'));
        $resultPage->getConfig()->getTitle()->prepend(__('RMA Listing'));
        return $resultPage;
    }
}
