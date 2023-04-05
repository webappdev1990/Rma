<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Reasons;
 
use Magento\Backend\App\Action;
 
class Delete extends Action
{
    public $reasonsModel;
 
    /**
     * @param Action\Context $context
     * @param \Softnoesis\Rma\Model\Reasons $model
     */
    public function __construct(
        Action\Context $context,
        \Softnoesis\Rma\Model\Reasons $reasonsModel
    ) {
        parent::__construct($context);
        $this->reasonsModel = $reasonsModel;
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
                $model = $this->reasonsModel;
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
