<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Customfield;

class NewAction extends \Magento\Backend\App\Action
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
        $resultPage->setActiveMenu('Softnoesis_Rma::customfield')
                ->addBreadcrumb(__('RMA '), __('RMA Customfield'))
                ->addBreadcrumb(__('Manage RMA Resolution'), __('Manage RMA Customfield'));
        return $resultPage;
    }

    public function execute()
    {
        $id = '';
        $model = $this->customfieldModel;
        $this->coreRegistry->register('rma_customfield', $model);
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Customfield') : __('New Customfield'),
            $id ? __('Edit Customfield') : __('New Customfield')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Customfield'));
        $resultPage->getConfig()->getTitle()
            ->prepend(__('New Customfield'));

        return $resultPage;
    }
}
