<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Unserialize\Unserialize;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $storeManager;
    public $objectManager;
    protected $ordersdetails;
    protected $serializer;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Softnoesis\Rma\Model\ReasonsFactory $reasonsFactory,
        \Softnoesis\Rma\Model\ResolutionsFactory $resolutionsFactory,
        \Softnoesis\Rma\Model\ConditionsFactory $conditionsFactory,
        \Softnoesis\Rma\Model\Config\Source\Conditions $conditionsSource,
        \Softnoesis\Rma\Model\Config\Source\Reasons $reasonsSource,
        \Softnoesis\Rma\Model\Config\Source\Resolutions $resolutionsSource,
        \Softnoesis\Rma\Model\Config\Source\Status $statusSource,
        Unserialize $serializer,
        \Magento\Sales\Model\Order $orderModel
    ) {
    
        $this->objectManager   = $objectManager;
        $this->storeManager    = $storeManager;
        $this->request         = $context->getRequest();
        $this->reasonsFactory = $reasonsFactory;
        $this->resolutionsFactory = $resolutionsFactory;
        $this->conditionsFactory = $conditionsFactory;
        $this->conditionsSource = $conditionsSource;
        $this->reasonsSource = $reasonsSource;
        $this->resolutionsSource = $resolutionsSource;
        $this->statusSource = $statusSource;
        $this->_orderModelFactory = $orderModel;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue('magerma/general/enable', $storeId);
    }
    public function unserialize($dropdownValues)
    {
        return $dropdownValues;
    }
    
    public function isAllowGuestEnabled($storeId = null)
    {
        return $this->getConfigValue('magerma/general/allow_guest_user', $storeId);
    }
    
    public function getAdminStatus($storeId = null)
    {
        return $this->getConfigValue('magerma/general/adminstatus', $storeId);
    }
    
    public function getAdminConfirmStatus($storeId = null)
    {
        return $this->getConfigValue('magerma/general/confirm_shipping_status', $storeId);
    }
    
    public function getReasons()
    {
        return $this->reasonsFactory->create()->getCollection()
                ->addFieldToFilter('is_active', ['eq'=>\Softnoesis\Rma\Model\Reasons::IS_ACTIVE_ENABLE])
                ->setOrder('sort_order', 'ASC');
    }
    
    public function getResolutions()
    {
        return $this->resolutionsFactory->create()->getCollection()
                ->addFieldToFilter('is_active', ['eq'=>\Softnoesis\Rma\Model\Resolutions::IS_ACTIVE_ENABLE])
                ->setOrder('sort_order', 'ASC');
    }
    
    public function getConditions()
    {
        return $this->conditionsFactory->create()->getCollection()
                ->addFieldToFilter('is_active', ['eq'=>\Softnoesis\Rma\Model\Conditions::IS_ACTIVE_ENABLE])
                ->setOrder('sort_order', 'ASC');
    }
    
    public function getConditionOptionText($optionId)
    {
        return $this->conditionsSource->getOptionText($optionId);
    }
    
    public function getReasonOptionText($optionId)
    {
        return $this->reasonsSource->getOptionText($optionId);
    }
    
    public function getResolutionOptionText($optionId)
    {
        return $this->resolutionsSource->getOptionText($optionId);
    }
    
    public function getStatusOptionText($optionId)
    {
        return$this->statusSource->getOptionText($optionId);
    }
    
    public function getOrderAllowStatus($storeId = null)
    {
        return $this->getConfigValue('magerma/rma_policy/allow_status', $storeId);
    }
    
    public function getOrderDetails($orderid)
    {
        if (!$this->ordersdetails) {
            $this->ordersdetails = $this->_orderModelFactory->load($orderid);
        }
        return $this->ordersdetails;
    }
    
    public function getDepartmentName($storeId = null)
    {
        return $this->getConfigValue('magerma/rma_department/department_name', $storeId);
    }
    
    public function getDepartmentEmail($storeId = null)
    {
        return $this->getConfigValue('magerma/rma_department/department_email', $storeId);
    }
    
    public function getDepartmentAddress($storeId = null)
    {
        return $this->getConfigValue('magerma/rma_department/department_address', $storeId);
    }
    
    public function getShippingConfirmText($storeId = null)
    {
        return $this->getConfigValue('magerma/general/return_confirmation_text', $storeId);
    }
    
    public function getShippingConfirmLabel($storeId = null)
    {
        return $this->getConfigValue('magerma/general/return_confirmation_label', $storeId);
    }
    
    public function getStatusDropdown()
    {
        return $this->statusSource->toOptionArray();
    }
    
    public function getGuestTemplate($storeId = null)
    {
        return $this->getConfigValue('magerma/email_template/new_guest_template', $storeId);
    }
    
    public function getNewTemplate($storeId = null)
    {
        return $this->getConfigValue('magerma/email_template/new_email_template', $storeId);
    }
    
    public function getCommentTemplate($storeId = null)
    {
        return $this->getConfigValue('magerma/email_template/comment_email_template', $storeId);
    }
    
    public function getSenderName($storeId = null)
    {
        return $this->getConfigValue('trans_email_/dent_general/name', $storeId);
    }
    
    public function getSenderEmail($storeId = null)
    {
        return $this->getConfigValue('trans_email/ident_general/email', $storeId);
    }
    
    public function getFileSize($storeId = null)
    {
        $size =  $this->getConfigValue('magerma/rma_policy/max_attachment_size', $storeId);
        $fileSizeMb =  ($size) ? $size : 0 ;
        $fileSizeBytes = $fileSizeMb * 1000000;
        return  $fileSizeBytes;
    }
    
    public function getOrderDays($storeId = null)
    {
        return $this->getConfigValue('magerma/rma_policy/return_period', $storeId);
    }
}
