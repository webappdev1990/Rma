<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Customer;

use Magento\Framework\Serialize\SerializerInterface;

class Save extends \Softnoesis\Rma\Controller\Productlist
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    protected $_jsonHelper;
    protected $rmaListFactory;
    protected $moduledata;
    protected $order;
    private $serializer;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Softnoesis\Rma\Model\RmaFactory $rmaListFactory,
        \Softnoesis\Rma\Helper\Data $moduledata,
        SerializerInterface $serializer,
        \Magento\Sales\Model\Order $orderFactory
    ) {
        parent::__construct($context, $customerSession, $resultPageFactory, $storeManager);
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $requestInterface;
        $this->customerSession = $customerSession;
        $this->rmaListFactory = $rmaListFactory;
        $this->moduledata = $moduledata;
        $this->order = $orderFactory;
        $this->serializer = $serializer;
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $insertData = [];
        $resultRedirect = $this->resultRedirectFactory->create();
        $model =  $this->rmaListFactory->create();
        $order = $this->order->load($data['order_id']);
        if ($data && isset($data['items']) && $data['items']) {
            $insertData['order_id'] = $data['order_id'];
            $insertData['comment'] = $data['comment'];
            $insertData['by'] = $this->customerSession->getCustomer()->getName();
            $insertData['customer_id'] = $data['currnt_cust_id'];
            $insertData['adminstatus'] = $this->moduledata->getAdminStatus();
            $insertData['commentside']  = 'customeruser';
            if (isset($data['comment_image'])) {
                $insertData['comment_image'] = $this->serializer->serialize($data['comment_image']);
            }
            $flag = 0;

            foreach ($data['items'] as $product) {

                if (isset($product['is_return']) && $product['is_return']) {
                    $flag = 1;
                    $insertData['rmaitems'][] = $product;
                }
            }
            
            if ($flag == 1) {
                $model->setData($insertData);
                $model->save();
                $model->sendMail($model, $order);
                $this->messageManager->addSuccess(__('RMA request sent successfully. Soon admin will update RMA status, keep the follow up.'));
            } else {
                $this->messageManager->addErrorMessage(__('There is no product for rma.'));
                return $resultRedirect->setPath('magerma/customer/rmalist');
            }
            $data = $this->_getSession()->getFormData(true);
        }
        return $resultRedirect->setPath('magerma/customer/rmalist');
    }
}
