<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Reasons;

use Magento\Backend\App\Action\Context;
use Softnoesis\Rma\Controller\Adminhtml\Reasons as ReasonsController;

class Save extends ReasonsController
{
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $model = $this->initReasons();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model->setData($data);
            $this->_eventManager->dispatch(
                'rma_reasons_prepare_save',
                ['reasons' => $model, 'request' => $this->getRequest()]
            );
            $model->save();
            $this->messageManager->addSuccess(__('RMA Reasons Saved Successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
