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

class Save extends RmaController
{

    protected $rmaCommentsFactory;
    
    /**
     * @var \Softnoesis\Rma\Model\Rma
     */
    public $rmasModel;
    protected $order;
    protected $moduledata;
    private $serializer;
    
    public function __construct(
        Registry $registry,
        RmaFactory $rmasfactory,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Magento\Sales\Model\Order $orderFactory,
        \Softnoesis\Rma\Helper\Data $moduledata,
        SerializerInterface $serializer,
        Context $context
    ) {
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->order = $orderFactory;
        $this->moduledata = $moduledata;
        $this->serializer = $serializer;
        parent::__construct($registry, $rmasfactory, $context);
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $model = $this->_initModel();
        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['cutome']) && $data['cutome']) {
                $cutomefield = $data['cutome'];
                $data['custome_fields'] = $this->serializer->serialize($cutomefield);
            }
            $model->setData($data);
            $this->_eventManager->dispatch(
                'rma_rmas_prepare_save',
                ['rmas' => $model, 'request' => $this->getRequest()]
            );
            
            $model->save();
            
            if (isset($data['commentdata']) && $data['commentdata']) {
                $commnetModel =  $this->rmaCommentsFactory->create();
                $insertData = [];
                $insertData['comments'] = $data['commentdata']['comment'];
                    $insertData['by'] = $this->moduledata->getDepartmentName();
                    $insertData['rma_id'] = $data['rma_id'];
                    $insertData['commentside'] = "adminuser";
                if (isset($data['comment_image'])) {
                    $insertData['comment_image'] = $this->serializer->serialize($data['comment_image']);
                }
                $commnetModel->setData($insertData);
                $commnetModel->save();
                
                if (isset($data['commentdata']['is_customer_notified'])
                        && $data['commentdata']['is_customer_notified']) {
                    $order = $this->order->load($data['order_id']);
                    $model->sendMailComment($commnetModel, $order);
                }
            }
            
            $this->messageManager->addSuccess(__('RMA list Saved Successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
