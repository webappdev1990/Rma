<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

class NewAction extends \Softnoesis\Rma\Controller\Adminhtml\Rma
{
    
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if (!$orderId) {
            $customerId = $this->getRequest()->getParam('customer_id');
            $this->_redirect('magerma/rmas/chooseorder', ['customer_id' => $customerId]);
        } else {
            try {
                $this->_initCreateModel();
                $this->_initModel();
//                if (!$this->_objectManager->get('Magento\Rma\Helper\Data')->canCreateRma($orderId, true)) {
//                    $this->messageManager->addError(__('There are no applicable items for return in this order.'));
//                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('magerma/rmas');
                return;
            }

            $this->_initAction();
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('New Return'));
            $this->_view->renderLayout();
        }
    }
}
