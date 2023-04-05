<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Softnoesis\Rma\Model\StatusesFactory;
use Magento\Framework\Registry;

abstract class Statuses extends Action
{
    /**
     * statues factory
     *
     * @var StatusesFactory
     */
    public $statusesFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;

    /**
     * @param Registry $registry
     * @param CategoryFactory $CategoryFactory
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        StatusesFactory $statusesFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->statusesFactory = $statusesFactory;
        parent::__construct($context);
    }

    /**
     * @return \Softnoesis\Rma\Model\Statues
     */
    public function initStatues()
    {
        $statusId  = (int) $this->getRequest()->getParam('id');
        $statues = $this->statusesFactory->create();
        if ($statusId) {
            $statues->load($statusId);
        }
        $this->coreRegistry->register('rma_statuses', $statues);
        return $statues;
    }

    public function filterData($data)
    {
        return $data;
    }
}
