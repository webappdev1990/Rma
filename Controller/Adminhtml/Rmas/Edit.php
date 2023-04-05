<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

class Edit extends \Magento\Backend\App\Action
{

    public $coreRegistry = null;
    public $resultPageFactory;
    public $rmasModel;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Model\Rma $rmasModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->rmasModel = $rmasModel;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softnoesis_Rma::rmalist');
    }
   
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::mage_rma')
                ->addBreadcrumb(__('RMA '), __('RMA Rma'))
                ->addBreadcrumb(__('Manage RMA list'), __('Manage RMA List'));
        return $resultPage;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->rmasModel;
        
        
        if ($id) {
            $model->load($id);
            
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Rma no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->getLayout()->getBlock('rma_list_items_block')->setRmaId($id);
                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->coreRegistry->register('rma_rmas', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Rma edit') : __('New Rma edit'),
            $id ? __('Edit Rma edit') : __('New Rma edit')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Rma edit'));
        $resultPage->getConfig()->getTitle()
                ->prepend($model->getId() ? $model->getTitle() : __('New Rma'));
        return $resultPage;
    }
}
