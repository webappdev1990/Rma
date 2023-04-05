<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Customer;

use Magento\Framework\Unserialize\Unserialize;
use Magento\Framework\Serialize\SerializerInterface;

class Rmaview extends \Magento\Framework\View\Element\Template
{
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    protected $_orderCollectionFactory;
    protected $orders;
    protected $rmadetails;
    protected $_orderModelFactory;
    protected $rmaFactory;
    protected $ordersdetails;
    protected $rmaItems;
    protected $rmaCommentsFactory;
    protected $rmaComments;
    protected $moduledata;
    protected $serializer;
    protected $unserialize;
   
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Softnoesis\Rma\Helper\Data $moduledata,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Softnoesis\Rma\Model\RmaItemsFactory $rmaItemsFactory,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Framework\View\Asset\Repository $assetRepository,
        Unserialize $serializer,
        SerializerInterface $unserialize,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
        $this->_countryFactory = $countryFactory;
         $this->serializer = $serializer;
        $this->_productloader = $_productloader;
        $this->moduledata = $moduledata;
        $this->_orderModelFactory = $orderModel;
        $this->rmaFactory = $rmaFactory;
        $this->rmaItemsFactory = $rmaItemsFactory;
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->priceHelper = $priceHelper;
        $this->assetRepository = $assetRepository;
        $this->unserialize = $unserialize;
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
        return $this->getUrl('magerma/customer/rmalist');
    }

    public function unserialize($dropdownValues)
    {
        return $dropdownValues;
    }
    public function unserializeValue($unserializeValue)
    {
        return $this->unserialize->unserialize($unserializeValue);
    }
    
    public function getOrders()
    {
        $customerId = $this->_customerSession->getCustomer()->getId();
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
    
    public function getRmaDetails($rmaid)
    {
        if (!$this->rmadetails) {
            $this->rmadetails = $this->rmaFactory->create()->load($rmaid);
        }
        return $this->rmadetails;
    }
    
    public function getRmaItems($rmaid)
    {
        if (!$this->rmaItems) {
            $this->rmaItems = $this->rmaItemsFactory->create()->getCollection()
                    ->addFieldToFilter("rma_id", $rmaid);
        }
        return $this->rmaItems;
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
    
    public function getConditionOptionText($optionId)
    {
        $condition = $this->moduledata->getConditionOptionText($optionId);
        return $condition;
    }
    
    public function getReasonOptionText($optionId)
    {
        $reason = $this->moduledata->getReasonOptionText($optionId);
        return $reason;
    }
    
    public function getStatusOptionText($optionId)
    {
        $status = $this->moduledata->getStatusOptionText($optionId);
        return $status;
    }
    
    public function getResolutionOptionText($optionId)
    {
        $resolution = $this->moduledata->getResolutionOptionText($optionId);
        return $resolution;
    }
    
    public function getCommentRequestUrl()
    {
        return $this->getUrl('magerma/customer/savecomment');
    }
    
    public function getRmaComments($rmaid)
    {
        if (!$this->rmaComments) {
            $this->rmaComments = $this->rmaCommentsFactory->create()->getCollection()
                    ->addFieldToFilter("rma_id", $rmaid);
        }
        return $this->rmaComments;
    }
    
    public function getShippingConfirmText()
    {
        return $this->moduledata->getShippingConfirmText();
    }
    
    public function getShippingConfirmLabel()
    {
        return $this->moduledata->getShippingConfirmLabel();
    }
    
    public function getShippingUrl()
    {
        return $this->getUrl('magerma/customer/shippingconfirm', ['_current' => true, '_use_rewrite' => true]);
    }
    
    public function getPrintRmaUrl()
    {
        return $this->getUrl('magerma/customer/shippingprint', ['_current' => true, '_use_rewrite' => true]);
    }
    
    public function getPriceFormat($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }
    
    public function getFileSize()
    {
        return $this->moduledata->getFileSize();
    }
}
