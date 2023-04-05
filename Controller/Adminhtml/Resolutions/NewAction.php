<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Resolutions;

class NewAction extends \Magento\Backend\App\Action
{
    public $coreRegistry = null;
    public $resultPageFactory;
    public $resolutionsModel;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Model\Resolutions $resolutionsModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->resolutionsModel = $resolutionsModel;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softnoesis_Rma::resolutions');
    }
   
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Softnoesis_Rma::resolutions')
                ->addBreadcrumb(__('RMA '), __('RMA Resolutions'))
                ->addBreadcrumb(__('Manage RMA Resolution'), __('Manage RMA Resolutions'));
        return $resultPage;
    }

    public function execute()
    {
        $id = '';
        $model = $this->resolutionsModel;
        $this->coreRegistry->register('rma_resolutions', $model);
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Resolution') : __('New Resolution'),
            $id ? __('Edit Resolution') : __('New Resolution')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Resolution'));
        $resultPage->getConfig()->getTitle()
            ->prepend(__('New Resolution'));

        return $resultPage;
    }
}
