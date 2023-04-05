<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Adminhtml\Statuses;

class Edit extends \Magento\Backend\App\Action
{
    public $coreRegistry = null;
    public $resultPageFactory;
    public $statuesModel;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Model\Statuses $statuesModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->statuesModel = $statuesModel;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softnoesis_Rma::statuses');
    }
   
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::rma_statuses')
                ->addBreadcrumb(__('RMA '), __('RMA Statuses'))
                ->addBreadcrumb(__('Manage RMA Categories'), __('Manage RMA Statuses'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->statuesModel;
        
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Status no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->coreRegistry->register('rma_statuses', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Rma Status') : __('New Rma Status'),
            $id ? __('Edit Rma Status') : __('New Rma Status')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Rma Statuses'));
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? $model->getTitle() : __('New Rma Status'));
        return $resultPage;
    }
}
