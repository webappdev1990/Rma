<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Customer;

class Rmanew extends \Magento\Framework\View\Element\Template
{
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    protected $_orderCollectionFactory;
    protected $orders;
    protected $ordersdetails;
    protected $_orderModel;
    protected $rmaFactory;
    protected $rmaItems;
    protected $moduledata;
    protected $customfieldFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Softnoesis\Rma\Helper\Data $moduledata,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Magento\Framework\Stdlib\DateTime\Timezone $_stdTimezone,
        \Softnoesis\Rma\Model\ResourceModel\Customfield\CollectionFactory $customfieldFactory,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderModelFactory = $orderModel;
        $this->_countryFactory = $countryFactory;
        $this->_productloader = $_productloader;
        $this->moduledata = $moduledata;
        $this->rmaFactory = $rmaFactory;
        $this->_stdTimezone = $_stdTimezone;
        $this->customfieldFactory = $customfieldFactory;
        parent::__construct($context, $data);
    }
    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
    
    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getUrl('magerma/customer/rmanew');
    }
    
    public function getOrders()
    {
        $allowStatus = $this->moduledata->getOrderAllowStatus();
        $toTime = $this->_stdTimezone->date()->format('Y-m-d H:i:s');
        $fromTime = $this->getDayswithdate();
        $customerId = $this->_customerSession->getCustomer()->getId();
        if (!$this->orders) {
            $this->orders = $this->_orderCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'customer_id',
                $customerId
            )
            ->addFieldToFilter('status', ['in'=> $allowStatus])
            //->addFieldToFilter('created_at', array('from' => $midnightToday,'to' => $rightNow))
            ->setOrder('created_at', 'desc');
        }
        return $this->orders;
    }
    
    public function getOrderDetails($orderid)
    {
        if (!$this->ordersdetails) {
            $this->ordersdetails = $this->_orderModelFactory->create()->load($orderid);
        }
        return $this->ordersdetails;
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
    
    public function getReasons()
    {
        return $this->moduledata->getReasons();
    }
    
    public function getResolutions()
    {
        return $this->moduledata->getResolutions();
    }
    
    public function getConditions()
    {
        return $this->moduledata->getConditions();
    }
    
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomer()->getId();
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
    
    public function getDayswithdate()
    {
        //$orderDays = $this->moduledata->getOrderDays();
        $orderDays = 2;
        $days = ($orderDays) ? $orderDays : 2 ;
        $date = $this->_stdTimezone->date()->format('Y-m-d H:i:s');
        $date = strtotime("-".$days." days", strtotime($date));
        date("Y-m-d H:i:s", $date);
        return  date("Y-m-d H:i:s", $date);
    }
    
    public function getFileSize()
    {
        return $this->moduledata->getFileSize();
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
}
