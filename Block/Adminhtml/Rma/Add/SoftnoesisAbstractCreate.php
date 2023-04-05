<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma\Add;

abstract class SoftnoesisAbstractCreate extends \Magento\Backend\Block\Widget
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistryData = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $contextData
     * @param \Magento\Framework\Registry $registryData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $contextData,
        \Magento\Framework\Registry $registryData,
        array $data = []
    ) {
        $this->_coreRegistryData = $registryData;
        parent::__construct($contextData, $data);
    }

    /**
     * Retrieve create order model object
     *
     * @return \Softnoesis\Rma\Model\Rma\Create
     */
    public function getCreateModelRma()
    {
        return $this->_coreRegistryData->registry('rma_create_model');
    }

    /**
     * Retrieve customer identifier
     *
     * @return int
     */
    public function getCustomerId()
    {
        return (int)$this->getCreateModelRma()->getCustomerId();
    }

    /**
     * Retrieve customer identifier
     *
     * @return int
     */
    public function getStoreId()
    {
        return (int)$this->getCreateModelRma()->getStoreId();
    }

    /**
     * Retrieve customer object
     *
     * @return int
     */
    public function getCustomer()
    {
        return $this->getCreateModelRma()->getCustomer();
    }

    /**
     * Retrieve customer name
     *
     * @return int
     */
    public function getCustomerName()
    {
        return $this->escapeHtml($this->getCustomer()->getName());
    }

    /**
     * Retrieve order identifier
     *
     * @return int
     */
    public function getOrderId()
    {
        return (int)$this->getCreateModelRma()->getOrderId();
    }

    /**
     * Set Customer Id
     *
     * @param int $id
     * @return void
     */
    public function setCustomerId($id)
    {
        $this->getCreateModelRma()->setCustomerId($id);
    }

    /**
     * Set Order Id
     *
     * @param int $id
     * @return mixed
     */
    public function setOrderId($id)
    {
        return $this->getCreateModelRma()->setOrderId($id);
    }
}
