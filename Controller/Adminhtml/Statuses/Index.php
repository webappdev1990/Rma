<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Adminhtml\Statuses;

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
        return $this->_authorization->isAllowed('Softnoesis_Rma::statuses');
    }
    // @codingStandardsIgnoreEnd
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::rma_statuses');
        $resultPage->addBreadcrumb(__('Rma'), __('Rma'));
        $resultPage->addBreadcrumb(__('Manage RMA statuses'), __('Manage RMA statuses'));
        $resultPage->getConfig()->getTitle()->prepend(__('RMA statuses'));
        return $resultPage;
    }
}
