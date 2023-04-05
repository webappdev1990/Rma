<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Adminhtml\Statuses;

use Softnoesis\Rma\Controller\Adminhtml\Statuses;
use Magento\Backend\App\Action\Context;
use Softnoesis\Rma\Controller\Adminhtml\Statuses as StatusesController;

class Save extends StatusesController
{
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $model = $this->initStatues();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model->setData($data);
            $this->_eventManager->dispatch(
                'rma_statuses_prepare_save',
                ['statuses' => $model, 'request' => $this->getRequest()]
            );
            $model->save();
            $this->messageManager->addSuccess(__('RMA Statuses Saved Successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
