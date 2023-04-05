<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma\Add;

class Header extends \Softnoesis\Rma\Block\Adminhtml\Rma\Add\SoftnoesisAbstractCreate
{
    /**
     * Create new rma content
     *
     * @return string
     */
    protected function _toHtml()
    {
        $customerId = $this->getCustomerId();
        $storeId = $this->getStoreId();
        $out = '';
        if ($customerId && $storeId) {
            $storeName = $this->_storeManager->getStore($storeId)->getName();
            $customerName = $this->getCustomerName();
            $out .= __('Create New RMA for %1 in %2', $customerName, $storeName);
        } elseif ($customerId) {
            $out .= __('Create New RMA for %1', $this->getCustomerName());
        } else {
            $out .= __('Create New RMA');
        }
        $out = $this->escapeHtml($out);
        $out = '<h3 class="icon-head head-sales-order">' . $out . '</h3>';
        return $out;
    }
}
