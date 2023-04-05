<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Customfield;

class Edit extends \Magento\Backend\App\Action
{

    public $coreRegistry = null;
    public $resultPageFactory;
    public $customfieldModel;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Model\Customfield $customfieldModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->customfieldModel = $customfieldModel;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softnoesis_Rma::customfield');
    }
   
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::rma_customfield')
                ->addBreadcrumb(__('RMA '), __('RMA Customfield'))
                ->addBreadcrumb(__('Manage RMA Customfield'), __('Manage RMA Customfield'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->customfieldModel;
        
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Customfield no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->coreRegistry->register('rma_customfield', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Rma Customfield') : __('New Rma Customfield'),
            $id ? __('Edit Rma Customfield') : __('New Rma Customfield')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Rma Customfield'));
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? $model->getTitle() : __('New Rma Customfield'));
        return $resultPage;
    }
}
