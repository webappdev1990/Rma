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
use Softnoesis\Rma\Model\ResolutionsFactory;
use Magento\Framework\Registry;

abstract class Resolutions extends Action
{
    /**
     * Resolutions factory
     *
     * @var ResolutionsFactory
     */
    public $resolutionsFactory;

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
        ResolutionsFactory $resolutionsFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->resolutionsFactory = $resolutionsFactory;
        parent::__construct($context);
    }

    /**
     * @return \Softnoesis\Rma\Model\Resolutions
     */
    public function initResolutions()
    {
        $resolutionId  = (int) $this->getRequest()->getParam('id');
        $resolutions = $this->resolutionsFactory->create();
        if ($resolutionId) {
            $resolutions->load($resolutionId);
        }
        $this->coreRegistry->register('rma_resolutions', $resolutions);
        return $resolutions;
    }

    public function filterData($data)
    {
        return $data;
    }
}
