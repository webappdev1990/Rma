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
use Softnoesis\Rma\Model\ReasonsFactory;
use Magento\Framework\Registry;

abstract class Reasons extends Action
{
    /**
     * statues factory
     *
     * @var ReasonsFactory
     */
    public $reasonsFactory;

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
        ReasonsFactory $reasonsFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->reasonsFactory = $reasonsFactory;
        parent::__construct($context);
    }

    /**
     * @return \Softnoesis\Rma\Model\Reasons
     */
    public function initReasons()
    {
        $reasonsId  = (int) $this->getRequest()->getParam('id');
        $reasons = $this->reasonsFactory->create();
        if ($reasonsId) {
            $reasons->load($reasonsId);
        }
        $this->coreRegistry->register('rma_reasons', $reasons);
        return $reasons;
    }

    public function filterData($data)
    {
        return $data;
    }
}
