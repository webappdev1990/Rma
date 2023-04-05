<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Customer;

class Rmaview extends \Softnoesis\Rma\Controller\Productlist
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
        
        $rmaId = $this->request->getParam('rma_id');
        $title = 'RMA #' .$rmaId.' - Approved';
        $resultRedirect->getConfig()->getTitle()->set(__($title));
        $blockInstance = $resultRedirect->getLayout()->getBlock('customer.rma.view')->setRmaId($rmaId);
        
        return $resultRedirect;
    }
}
