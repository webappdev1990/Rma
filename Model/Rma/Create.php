<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Rma;

/**
 * RMA create model
 */
class Create extends \Magento\Framework\DataObject
{
    /**
     * Customer object, RMA's order attached to
     *
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customerData = null;

    /**
     * Order object, RMA attached to
     *
     * @var \Magento\Sales\Model\Order
     */
    protected $_orderData = null;

    /**
     * Customer factory
     *
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerDataFactory;

    /**
     * Sales order factory
     *
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderDataFactory;

    /**
     * @param \Magento\Customer\Model\CustomerFactory $customerDataFactory
     * @param \Magento\Sales\Model\OrderFactory $orderDataFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerDataFactory,
        \Magento\Sales\Model\OrderFactory $orderDataFactory,
        array $data = []
    ) {
        $this->_customerDataFactory = $customerDataFactory;
        $this->_orderDataFactory = $orderDataFactory;
        parent::__construct($data);
    }

    /**
     * Get Customer object
     *
     * @param null|int $customerdataId
     * @return \Magento\Customer\Model\Customer|null
     */
    public function getCustomer($customerdataId = null)
    {
        if ($this->_customerData === null) {
            if ($customerdataId === null) {
                $customerdataId = $this->getCustomerId();
            }
            $customerdataId = intval($customerdataId);

            if ($customerdataId) {
                /** @var $customer \Magento\Customer\Model\Customer */
                $customer = $this->_customerDataFactory->create();
                $customer->load($customerdataId);
                $this->_customerData = $customer;
            } elseif (intval($this->getOrderId())) {
                return $this->getCustomer($this->getOrder()->getCustomerId());
            }
        }
        return $this->_customerData;
    }

    /**
     * Get Order object
     *
     * @param null|int $orderDataId
     * @return \Magento\Sales\Model\Order|null
     */
    public function getOrder($orderDataId = null)
    {
        if ($this->_orderData === null) {
            if ($orderDataId === null) {
                $orderDataId = $this->getOrderId();
            }
            $orderDataId = intval($orderDataId);
            if ($orderDataId) {
                /** @var $order \Magento\Sales\Model\Order */
                $order = $this->_orderDataFactory->create();
                $order->load($orderDataId);
                $this->_orderData = $order;
            }
        }
        return $this->_orderData;
    }
}
