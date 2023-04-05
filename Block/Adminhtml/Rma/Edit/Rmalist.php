<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma\Edit;

use Magento\Framework\Unserialize\Unserialize;
use Magento\Framework\Serialize\SerializerInterface;

class Rmalist extends \Magento\Backend\Block\Template
{
    
    /**
     * @var string
     */
    protected $_template = 'Softnoesis_Rma::rma/rmalist.phtml';
    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $_context;
    
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
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
    protected $moduleHelper;
    protected $customfieldFactory;
    protected $priceHelper;
    protected $serializer;
    protected $unserialize;


    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Softnoesis\Rma\Model\RmaItemsFactory $rmaItemsFactory,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Magento\Framework\App\Request\Http $request,
        \Softnoesis\Rma\Helper\Data $moduleHelper,
        Unserialize $serializer,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Softnoesis\Rma\Model\ResourceModel\Customfield\CollectionFactory $customfieldFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        SerializerInterface $unserialize,
        array $data = []
    ) {
        $this->_customerSession = $customerSession;
         $this->serializer = $serializer;
        $this->_countryFactory = $countryFactory;
        $this->_productloader = $_productloader;
        $this->_orderModelFactory = $orderModel;
        $this->rmaFactory = $rmaFactory;
        $this->rmaItemsFactory = $rmaItemsFactory;
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->request = $request;
        $this->moduleHelper = $moduleHelper;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->customfieldFactory = $customfieldFactory;
        $this->priceHelper = $priceHelper;
        $this->unserialize = $unserialize;
        parent::__construct($context, $data);
    }
    
    public function getCurrentRmaId()
    {
        $rma_id = $this->request->getParam('id');
        return $rma_id;
    }

    public function unserialize($dropdownValues)
    {
        return $dropdownValues;
    }
    public function unserializeValue($unserializeValue)
    {
        return $this->unserialize->unserialize($unserializeValue);
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
        $condition = $this->moduleHelper->getConditionOptionText($optionId);
        return $condition;
    }
    
    public function getReasonOptionText($optionId)
    {
        $reason = $this->moduleHelper->getReasonOptionText($optionId);
        return $reason;
    }
    
    public function getResolutionOptionText($optionId)
    {
        $resolution = $this->moduleHelper->getResolutionOptionText($optionId);
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
        return $this->getUrl('magerma/rmas/save', ['_current' => true, '_use_rewrite' => true]);
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
