<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma;

use Magento\Framework\Unserialize\Unserialize;
use Magento\Framework\Serialize\SerializerInterface;

class NewRma extends \Magento\Backend\Block\Template
{
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    protected $customfieldFactory;
    protected $moduleHelper;
    protected $_orderCollectionFactory;
    protected $orders;
    protected $ordersdetails;
    protected $rmaFactory;
    protected $priceHelper;
    protected $serializer;
    protected $unserialize;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Rma\Helper\Data $rmaData
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Helper\Data $moduleHelper,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Softnoesis\Rma\Model\ResourceModel\Customfield\CollectionFactory $customfieldFactory,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        Unserialize $serializer,
        SerializerInterface $unserialize,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_countryFactory = $countryFactory;
        $this->_productloader = $_productloader;
         $this->serializer = $serializer;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->moduleHelper = $moduleHelper;
        $this->customfieldFactory = $customfieldFactory;
        $this->rmaFactory = $rmaFactory;
        $this->priceHelper = $priceHelper;
        $this->unserialize = $unserialize;
        parent::__construct($context, $data);
    }

    public function unserialize($dropdownValues)
    {
        return $dropdownValues;
    }
    public function unserializeValue($unserializeValue)
    {
        return $this->unserialize->unserialize($unserializeValue);
    }
    
    public function getCurrentOrderId()
    {
        $order_id = $this->_coreRegistry->registry('current_order')->getId();
        return $order_id;
    }
    
    public function getOrderDetails()
    {
        return $this->_coreRegistry->registry('current_order');
    }
    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl(
            'magerma/rmas/save',
            ['order_id' => $this->_coreRegistry->registry('current_order')->getId()]
        );
    }
    public function getReasons()
    {
        return $this->moduleHelper->getReasons();
    }
    
    public function getResolutions()
    {
        return $this->moduleHelper->getResolutions();
    }
    
    public function getConditions()
    {
        return $this->moduleHelper->getConditions();
    }
    public function getCountryname($countryCode)
    {
        $country = $this->_countryFactory->create()->loadByCode($countryCode);
        return $country->getName();
    }
    
    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }
    
    public function getCustomFieldCollection()
    {
        return $this->customfieldFactory->create()
            ->addFieldToFilter(
                'is_active',
                ['eq'=>\Softnoesis\Rma\Model\Customfield::IS_ACTIVE_ENABLE]
            )->setOrder(
                'sort_order',
                'ASC'
            );
    }
    
    public function getStatusDropdown()
    {
        $status = $this->moduleHelper->getStatusDropdown();
        return $status;
    }
    
    public function getFormAction()
    {
        return $this->getUrl('magerma/rmas/newsave', ['_current' => false, '_use_rewrite' => true]);
    }
    
    public function getOrders($customerId)
    {
        if (!$this->orders) {
            $this->orders = $this->_orderCollectionFactory->create()
                ->addFieldToSelect(
                    '*'
                )->addFieldToFilter(
                    'customer_id',
                    $customerId
                )->setOrder(
                    'created_at',
                    'desc'
                );
        }
        return $this->orders;
    }
    
    public function getDepartmentName()
    {
        return $this->moduleHelper->getDepartmentName();
    }
    
    public function getDepartmentEmail()
    {
        return $this->moduleHelper->getDepartmentEmail();
    }
    
    public function getDepartmentAddress()
    {
        return $this->moduleHelper->getDepartmentAddress();
    }
    
    public function getReturnQty($productId, $orderId)
    {
        $return = $this->rmaFactory->create()->getReturnQty($productId, $orderId);
        $pqty = 0;
        if (!empty($return)) {
            foreach ($return as $qty) {
                $pqty = $pqty + $qty;
            }
        }
        return $pqty;
    }
    
    public function getExistRmaIds($productId, $orderId)
    {
        $return = $this->rmaFactory->create()->getExistRmaIds($productId, $orderId);
        return $return;
    }
    
    public function getPriceFormat($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }
    
    public function getFileSize()
    {
        return $this->moduleHelper->getFileSize();
    }
}
