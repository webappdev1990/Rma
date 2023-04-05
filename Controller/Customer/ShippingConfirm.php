<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Customer;

class ShippingConfirm extends \Softnoesis\Rma\Controller\Productlist
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    protected $_jsonHelper;
    protected $rmaListFactory;
    protected $rmaFactory;
    protected $moduledata;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Softnoesis\Rma\Model\RmaFactory $rmaFactory,
        \Softnoesis\Rma\Helper\Data $moduledata
    ) {
        parent::__construct($context, $customerSession, $resultPageFactory, $storeManager);
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $requestInterface;
        $this->customerSession = $customerSession;
        $this->moduledata = $moduledata;
        $this->rmaFactory = $rmaFactory;
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        //echo $this->moduledata->getAdminConfirmStatus(); exit;
        $rmaModel =  $this->rmaFactory->create()->load($data['rma_id']);
        if ($data) {
            $rmaModel->setConfirmShipping(1);
            $rmaModel->setAdminstatus($this->moduledata->getAdminConfirmStatus());
            $rmaModel->save();
            $this->messageManager->addSuccess(__('RMA Shipping confirmation successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/rmaview', ['rma_id' => $rmaModel->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath(
                'magerma/customer/rmaview',
                ['rma_id' => $rmaModel->getRmaId(), '_current' => true]
            );
        }
        return $resultRedirect->setPath(
            'magerma/customer/rmaview',
            ['rma_id' => $rmaModel->getRmaId(), '_current' => true]
        );
    }
}
