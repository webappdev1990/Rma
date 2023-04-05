<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Conditions;

class Edit extends \Magento\Backend\App\Action
{

    public $coreRegistry = null;
    public $resultPageFactory;
    public $conditionsModel;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Model\Conditions $conditionsModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->conditionsModel = $conditionsModel;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softnoesis_Rma::conditions');
    }
   
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::rma_conditions')
                ->addBreadcrumb(__('RMA '), __('RMA Conditions'))
                ->addBreadcrumb(__('Manage RMA Categories'), __('Manage RMA Conditions'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->conditionsModel;
        
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Condition no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->coreRegistry->register('rma_conditions', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Rma Condition') : __('New Rma Condition'),
            $id ? __('Edit Rma Condition') : __('New Rma Condition')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Rma Conditions'));
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? $model->getTitle() : __('New Rma Condition'));
        return $resultPage;
    }
}
