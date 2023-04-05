<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model;

class Rma extends \Magento\Framework\Model\AbstractModel
{

    const RMA_ID = 'rma_id'; // We define the id fieldname
    
    const IS_ACTIVE_ENABLE = 1;
    const IS_ACTIVE_DISABLED = 2;
    
    /**
     * Prefix of model events names
     *
     * @var string
     */

    public $eventPrefix = 'rma'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    public $eventObject = 'list'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    public $idFieldName = self::RMA_ID;
    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;
    protected $moduledata;
    protected $rmaItemsFactory;
    protected $rmaCommentsFactory;
    protected $_transportBuilder;
    /**
     * @var AddressRenderer
     */
    protected $addressRenderer;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Softnoesis\Rma\Helper\Data $moduledata,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Softnoesis\Rma\Model\RmaCommentsFactory $rmaCommentsFactory,
        \Softnoesis\Rma\Model\RmaItemsFactory $rmaItemsFactory,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->rmaCommentsFactory = $rmaCommentsFactory;
        $this->rmaItemsFactory = $rmaItemsFactory;
        $this->moduledata = $moduledata;
        $this->inlineTranslation = $inlineTranslation;
        $this->addressRenderer = $addressRenderer;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    
    public function _construct()
    {
        $this->_init('Softnoesis\Rma\Model\ResourceModel\Rma');
    }
    
    public function getAvailableStatuses()
    {
        return [self::IS_ACTIVE_ENABLE => __('Enabled'), self::IS_ACTIVE_DISABLED => __('Disabled')];
    }
    
    public function afterSave()
    {
        $this->saveCommentRelation($this);
        $this->saveProductRelation($this);
        return parent::afterSave($this);
    }
    
    public function saveProductRelation($rma)
    {
        $product = [];
        $rmaId = $rma->getRmaId();
        $orderId = $rma->getOrderId();
        $rmaItems = $rma->getRmaitems();
        if ($rmaItems === null) {
            return $this;
        }
        foreach ($rmaItems as $key => $rmaItem) {
            $product['rma_id'] =  $rmaId;
            $product['product_id'] = $rmaItem['product_id'];
            $product['qty'] = $rmaItem['qty_requested'];
            $product['reason'] = $rmaItem['reason_id'];
            $product['condition'] = $rmaItem['condition_id'];
            $product['resolution'] = $rmaItem['resolution_id'];
            $product['order_id'] = $orderId;
            $rmaItemsModel = $this->rmaItemsFactory->create();
            $rmaItemsModel->setData($product);
            $rmaItemsModel->save();
            $rmaItemsModel->unsetData($product);
        }
    }
    
    public function saveCommentRelation($rma)
    {
        $comment = [];
        $rmaId = $rma->getRmaId();
        //$rmaItems = $rma->getRmaItems();
        $rmaComment = $rma->getComment();
        
        if ($rmaComment === null) {
            return $this;
        }
        
        $comment['rma_id'] =  $rmaId;
        $comment['comments'] = $rmaComment;
        $comment['by'] =  $rma->getBy();
        $comment['comment_image'] = $rma->getCommentImage();
        
        $rmaCommentsModel = $this->rmaCommentsFactory->create();
        $rmaCommentsModel->setData($comment);
        $rmaCommentsModel->save();
    }
    
    public function getReturnQty($productId, $orderId)
    {
        return $this->getResource()->getReturnQty($productId, $orderId);
    }
    
    public function getExistRmaIds($productId, $orderId)
    {
        return $this->getResource()->getExistRmaIds($productId, $orderId);
    }
    
    /**
     * Get store object
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        if ($this->getOrder()) {
            return $this->getOrder()->getStore();
        }
        return $this->_storeManager->getStore();
    }
    
    public function sendMail($rma, $order)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $rma = $this->load($rma->getRmaId());
        if (!$this->moduledata->isEnabled()) {
            return $this;
        }
        
        $this->inlineTranslation->suspend();

        if ($order->getCustomerIsGuest()) {
            $template = $this->moduledata->getGuestTemplate();
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $template = $this->moduledata->getNewTemplate();
            $customerName = $order->getBillingAddress()->getName();
        }

        $sendTo = [
                    ['email' => $this->moduledata->getDepartmentEmail(), 'name' => $this->moduledata->getDepartmentName()],
                    ['email' => $order->getCustomerEmail(), 'name' => $customerName]
                ];
        $returnAddress = $this->moduledata->getDepartmentAddress();

        foreach ($sendTo as $recipient) {
            $transport = $this->_transportBuilder->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars(
                    [
                        'rma' => $rma,
                        'status' => $this->moduledata->getStatusOptionText($rma->getAdminstatus()),
                        'order' => $order,
                        'store' => $this->getStore(),
                        'return_address' => $returnAddress,
                        'formattedShippingAddress' => $this->addressRenderer->format(
                            $order->getShippingAddress(),
                            'html'
                        ),
                        'formattedBillingAddress' => $this->addressRenderer->format(
                            $order->getBillingAddress(),
                            'html'
                        ),
                    ]
                )
                ->setFrom([
                        'email' => $this->moduledata->getSenderEmail(),
                        'name' => "Store Owner"
                    ])
                    ->addTo($recipient['email'])
                ->getTransport();
            try {
                $transport->sendMessage();
            } catch (\Exception $e) {
                return $this;
            }
        }
        $this->inlineTranslation->resume();

        return $this;
    }
    
    public function sendMailComment($commnetModel, $order)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $rma = $this->load($commnetModel->getRmaId());
        if (!$this->moduledata->isEnabled()) {
            return $this;
        }
        
        $this->inlineTranslation->suspend();

        if ($order->getCustomerIsGuest()) {
            $template = $this->moduledata->getCommentTemplate();
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $template = $this->moduledata->getCommentTemplate();
            $customerName = $order->getBillingAddress()->getName();
        }

        $sendTo = [
                    ['email' => $this->moduledata->getDepartmentEmail(), 'name' => $this->moduledata->getDepartmentName()],
                    ['email' => $order->getCustomerEmail(), 'name' => $customerName]
                ];
        $returnAddress = $this->moduledata->getDepartmentAddress();

        foreach ($sendTo as $recipient) {
            $transport = $this->_transportBuilder->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId])
                ->setTemplateVars(
                    [
                        'rma' => $rma,
                        'status' => $this->moduledata->getStatusOptionText($rma->getAdminstatus()),
                        'order' => $order,
                        'store' => $this->getStore(),
                        'comment' =>$commnetModel->getComments(),
                        'return_address' => $returnAddress,
                        'formattedShippingAddress' => $this->addressRenderer->format(
                            $order->getShippingAddress(),
                            'html'
                        ),
                        'formattedBillingAddress' => $this->addressRenderer->format(
                            $order->getBillingAddress(),
                            'html'
                        ),
                    ]
                )
                ->setFrom([
                        'email' => $this->moduledata->getSenderEmail(),
                        'name' => "Store Owner"
                    ])
                    ->addTo($recipient['email'])
                ->getTransport();
            try {
                $transport->sendMessage();
            } catch (\Exception $e) {
                return $this;
            }
        }
        $this->inlineTranslation->resume();

        return $this;
    }
}
