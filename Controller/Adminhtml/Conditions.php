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
use Softnoesis\Rma\Model\ConditionsFactory;
use Magento\Framework\Registry;

abstract class Conditions extends Action
{
    /**
     * statues factory
     *
     * @var ConditionsFactory
     */
    public $conditionsFactory;

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
        ConditionsFactory $conditionsFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->conditionsFactory = $conditionsFactory;
        parent::__construct($context);
    }

    /**
     * @return \Softnoesis\Rma\Model\Conditions
     */
    public function initConditions()
    {
        $conditionsId  = (int) $this->getRequest()->getParam('id');
        $conditions = $this->conditionsFactory->create();
        if ($conditionsId) {
            $conditions->load($conditionsId);
        }
        $this->coreRegistry->register('rma_conditions', $conditions);
        return $conditions;
    }

    public function filterData($data)
    {
        return $data;
    }
}
