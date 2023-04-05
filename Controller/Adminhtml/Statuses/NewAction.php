<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Adminhtml\Statuses;

class NewAction extends \Magento\Backend\App\Action
{
    public $resultForwardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
   /**
    * Init actions
    *
    * @return \Magento\Backend\Model\View\Result\Page
    */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::statuses')
                ->addBreadcrumb(__('RMA '), __('RMA Status'))
                ->addBreadcrumb(__('Manage RMA Status'), __('Manage RMA Status'));
        return $resultPage;
    }
    
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(__('New Rma Status'), __('New Rma Status'));
        $resultPage->getConfig()->getTitle()->prepend(__('Rma Status'));
        $resultPage->getConfig()->getTitle()
                ->prepend(__('New Rma Status'));
        return $resultPage;
    }
}
