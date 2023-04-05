<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;

class Rma extends AbstractDb
{
    
    public function _construct()
    {
        $this->_init('md_rma_list', 'rma_id');
    }
    
    public function getReturnQty($productId, $orderId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('md_rma_products'),
            ['qty']
        )
                ->where("order_id = $orderId AND product_id = $productId");
        return $this->getConnection()->fetchCol($select);
    }
    
    public function getExistRmaIds($productId, $orderId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('md_rma_products'),
            ['rma_id']
        )
                ->where("order_id = $orderId AND product_id = $productId");
        return $this->getConnection()->fetchCol($select);
    }
}
