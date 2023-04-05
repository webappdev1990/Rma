<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;
 
use Magento\Backend\App\Action;
 
class Delete extends Action
{
    public $rmasModel;
 
    /**
     * @param Action\Context $context
     * @param \Softnoesis\Rma\Model\Rma $model
     */
    public function __construct(
        Action\Context $context,
        \Softnoesis\Rma\Model\Rma $rmasModel
    ) {
        parent::__construct($context);
        $this->rmasModel = $rmasModel;
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->rmasModel;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('item deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('item does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
