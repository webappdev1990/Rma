<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Customer;

class Rmalist extends \Magento\Framework\View\Element\Template
{
    
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    protected $ordersdetails;
    protected $moduledata;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Softnoesis\Rma\Model\RmaItemsFactory $rmaItemsFactory,
        \Magento\Sales\Model\OrderFactory $orderModel,
        \Softnoesis\Rma\Helper\Data $moduledata,
        array $data = []
    ) {
        $this->_rmaFactory = $rmaFactory;
        $this->_customerSession = $customerSession;
        $this->_rmaCommentsFactory = $rmaCommentsFactory;
        $this->_rmaItemsFactory = $rmaItemsFactory;
        $this->_orderModelFactory = $orderModel;
        $this->moduledata = $moduledata;
        parent::__construct($context, $data);
    }
    
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getRmas()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'rma.customer.history.pager'
            )
            ->setAvailableLimit([5=>5,10=>10,15=>15])
            ->setShowPerPage(true)
            ->setCollection(
                $this->getRmas()
            )
            ->setShowAmounts(false);
            
            $this->setChild('pager', $pager);
            $this->getRmas()->load();
        }
        return $this;
    }
    
    private function getRmaCollectionFactory()
    {
        //get values of current page
        $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 10;
        
        $collection = $this->_rmaFactory->create()->getCollection()
        ->addFieldToSelect(
            'rma_id'
        )->addFieldToSelect(
            'order_id'
        )->addFieldToSelect(
            'adminstatus'
        )->addFieldToSelect(
            'created_at'
        )->addFieldToFilter('customer_id', $this->getCustomerId());
                
        $collection->setOrder('rma_id', 'DESC');
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * @return bool|\Magento\Catalog\Model\Product
     */
    public function getRmas()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        return $this->getRmaCollectionFactory();
    }
    
    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
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
    public function getNewRequestUrl()
    {
        return $this->getUrl('magerma/customer/rmanew');
    }
    /**
     * @return string
     */
    public function getRmaRequestUrl()
    {
        return $this->getUrl('magerma/customer/rmaview');
    }
    
    public function getCustomerId()
    {
        return $this->_customerSession->getCustomer()->getId();
    }
    
    public function getOrderDetails($orderid)
    {
        $this->ordersdetails = $this->_orderModelFactory->create()->load($orderid);
        return $this->ordersdetails->getIncrementId();
    }
    
    public function getStatusOptionText($optionId)
    {
        $status = $this->moduledata->getStatusOptionText($optionId);
        return $status;
    }
}
