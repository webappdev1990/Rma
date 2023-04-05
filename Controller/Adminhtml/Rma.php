<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Softnoesis\Rma\Model\RmaFactory;
use Magento\Framework\Registry;

abstract class Rma extends Action
{
    /**
     * rmas factory
     *
     * @var RmaFactory
     */
    public $rmasFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * @param Registry $registry
     * @param CategoryFactory $CategoryFactory
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        RmaFactory $rmasFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->rmasFactory = $rmasFactory;
        parent::__construct($context);
    }

    /**
     * Initialize model
     *
     * @param string $requestParam
     * @return \Magento\Rma\Model\Rma
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initModel($requestParam = 'id')
    {
        /** @var $model \Magento\Rma\Model\Rma */
        $model = $this->_objectManager->create('Softnoesis\Rma\Model\Rma');
        $model->setStoreId($this->getRequest()->getParam('store', 0));

        $rmaId = $this->getRequest()->getParam($requestParam);
        if ($rmaId) {
            $model->load($rmaId);
            if (!$model->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('The wrong RMA was requested.'));
            }
            $this->coreRegistry->register('rma_rmas_list', $model);
            $orderId = $model->getOrderId();
        } else {
            $orderId = $this->getRequest()->getParam('order_id');
        }

        if ($orderId) {
            /** @var $order \Magento\Sales\Model\Order */
            $order = $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
            if (!$order->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('This is the wrong RMA order ID.'));
            }
            $this->coreRegistry->register('current_order', $order);
        }

        return $model;
    }

    public function filterData($data)
    {
        return $data;
    }
    
    /**
     * Init active menu and set breadcrumb
     *
     * @return \Magento\Rma\Controller\Adminhtml\Rma
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Softnoesis_Rma::Softnoesis_Rma_list');

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Returns'));
        return $this;
    }
    /**
     * Initialize model
     *
     * @return \Magento\Rma\Model\Rma\Create
     */
    protected function _initCreateModel()
    {
        /** @var $model \Magento\Rma\Model\Rma\Create */
        $model = $this->_objectManager->create('Softnoesis\Rma\Model\Rma\Create');
        $orderId = $this->getRequest()->getParam('order_id');
        $model->setOrderId($orderId);
        if ($orderId) {
            /** @var $order \Magento\Sales\Model\Order */
            $order = $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
            $model->setCustomerId($order->getCustomerId());
            $model->setStoreId($order->getStoreId());
        }
        $this->coreRegistry->register('rma_create_model', $model);
        return $model;
    }
}
