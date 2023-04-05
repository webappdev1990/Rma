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

class Savecomment extends \Softnoesis\Rma\Controller\Productlist
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    protected $_jsonHelper;
    protected $rmaListFactory;
    private $serializer;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Softnoesis\Rma\Model\RmaFactory $rmaListFactory,
        SerializerInterface $serializer,
        \Magento\Sales\Model\Order $orderFactory
    ) {
        parent::__construct($context, $customerSession, $resultPageFactory, $storeManager);
        $this->resultPageFactory = $resultPageFactory;
        $this->request = $requestInterface;
        $this->customerSession = $customerSession;
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->rmaListFactory = $rmaListFactory;
        $this->order = $orderFactory;
        $this->serializer = $serializer;
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $insertData = [];
        $resultRedirect = $this->resultRedirectFactory->create();
        $model =  $this->rmaCommentsFactory->create();
        $rmaModel =  $this->rmaListFactory->create();
        $order = $this->order->load($data['order_id']);
        if ($data) {
            $insertData['comments'] = htmlentities($data['comment']);
            $insertData['by'] = $this->customerSession->getCustomer()->getName();
            $insertData['rma_id'] = $data['rma_id'];
            $insertData['commentside'] = "customeruser";
            
            if (isset($data['comment_image'])) {
                $insertData['comment_image'] = $this->serializer->serialize($data['comment_image']);
            }
            
            $model->setData($insertData);
            $model->save();
            $rmaModel->sendMailComment($model, $order);
            $this->messageManager->addSuccess(__('RMA comment be added successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/rmaview', ['rma_id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath(
                'magerma/customer/rmaview',
                ['rma_id' => $model->getRmaId(), '_current' => true]
            );
        }
        return $resultRedirect->setPath(
            'magerma/customer/rmaview',
            ['rma_id' => $model->getRmaId(), '_current' => true]
        );
    }
}
