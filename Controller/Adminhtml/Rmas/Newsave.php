<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

use Magento\Backend\App\Action\Context;
use Softnoesis\Rma\Controller\Adminhtml\Rma as RmaController;
use Magento\Framework\Registry;
use Softnoesis\Rma\Model\RmaFactory;
use Magento\Framework\Serialize\SerializerInterface;

class Newsave extends RmaController
{
    protected $rmaCommentsFactory;
    protected $rmaListFactory;
    protected $moduledata;
    protected $order;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        Registry $registry,
        RmaFactory $rmasfactory,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Softnoesis\Rma\Model\RmaFactory $rmaListFactory,
        \Softnoesis\Rma\Helper\Data $moduledata,
        \Magento\Sales\Model\Order $orderFactory,
        SerializerInterface $serializer,
        Context $context
    ) {
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->moduledata = $moduledata;
        $this->rmaListFactory = $rmaListFactory;
        $this->order = $orderFactory;
        $this->serializer = $serializer;
        parent::__construct($registry, $rmasfactory, $context);
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        //echo "<pre>"; print_r($data); exit;
        $model =  $this->rmaListFactory->create();
        $order = $this->order->load($data['order_id']);
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['cutome']) && $data['cutome']) {
                $cutomefield = $data['cutome'];
                $insertData['custome_fields'] = $this->serializer->serialize($cutomefield);
            }
            $insertData['order_id'] = $data['order_id'];
            $insertData['comment'] = $data['comment'];
            $insertData['by'] = $this->moduledata->getDepartmentName();
            $insertData['customer_id'] = ($order->getCustomerId()) ? $order->getCustomerId(): '';
            $insertData['adminstatus'] = $data['adminstatus'];
            $insertData['commentside'] = "adminuser";
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
           /* if (isset($data['comment'])) {
                $insertData['comment'] = $data['comment'];
            }*/
//            echo "<pre>";
//            print_r($insertData);
//            die('test');
            $model->setData($insertData);
            $this->_eventManager->dispatch(
                'rma_rmas_prepare_save',
                ['rmas' => $model, 'request' => $this->getRequest()]
            );
            if ($flag == 1) {
                $model->save();
                if (isset($data['commentdata']['is_customer_notified'])
                    && $data['commentdata']['is_customer_notified']) {
                    $model->sendMail($model, $order);
                }
                $this->messageManager->addSuccess(__('RMA list Saved Successfully.'));
            } else {
                $this->messageManager->addErrorMessage(__('There is no product for rma.'));
                return $resultRedirect->setPath('*/*/index');
            }
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
