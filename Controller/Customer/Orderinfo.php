<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Customer;

class Orderinfo extends \Softnoesis\Rma\Controller\Productlist
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    protected $_jsonHelper;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\App\RequestInterface $requestInterface
    ) {
        parent::__construct($context, $customerSession, $resultPageFactory, $storeManager);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_jsonHelper = $jsonHelper;
        $this->request = $requestInterface;
        $this->customerSession = $customerSession;
    }
    
    public function execute()
    {
        $resultRedirect = $this->resultPageFactory->create();
        
        $orderId = $this->request->getParam('orderId');
        if ($orderId) {
            $blockInstance = $resultRedirect->getLayout()->getBlock('customer.rma.new.order')->setOrderId($orderId);
            $responseData['orderdetails'] = $blockInstance->toHtml();
        } else {
            $responseData['orderdetails'] = "<span>Please select order Id<span>";
        }
        /** Json Responce */
        $this->getResponse()->representJson($this->_jsonHelper->jsonEncode($responseData));
    }
}
