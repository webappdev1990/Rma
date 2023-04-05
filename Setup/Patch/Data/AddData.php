<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Setup\Patch\Data;

use Softnoesis\Rma\Model\StatusesFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddData implements DataPatchInterface
{
     private $statusesFactory;

    public function __construct(StatusesFactory $statusesFactory, ModuleDataSetupInterface $moduleDataSetup)
    {
         $this->statusesFactory = $statusesFactory;
         $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
         $this->moduleDataSetup->getConnection()->startSetup();

         $status = [
           [
               'title' => 'Pending',
               'code' => 'pending',
               'sort_order' => '10',
               'is_active' => 1
           ],
           [
               'title' => 'Approved',
               'code' => 'approved',
               'sort_order' => '10',
               'is_active' => 1
           ],
           [
               'title' => 'Package Sent',
               'code' => 'package_sent',
               'sort_order' => '10',
               'is_active' => 1
           ],
           [
               'title' => 'Processing',
               'code' => 'processing',
               'sort_order' => '10',
               'is_active' => 1
           ],
           [
               'title' => 'Rejected',
               'code' => 'rejected',
               'sort_order' => '10',
               'is_active' => 1
           ],
           [
               'title' => 'Close',
               'code' => 'close',
               'sort_order' => '10',
               'is_active' => 1
           ]
         ];
         foreach ($status as $data) {
              $this->statusesFactory->create()->setData($data)->save();
         }
         $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
         return [];
    }

    public function getAliases()
    {
         return [];
    }
}
