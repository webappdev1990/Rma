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
use Softnoesis\Rma\Model\CustomfieldFactory;
use Magento\Framework\Registry;

abstract class Customfield extends Action
{
    /**
     * statues factory
     *
     * @var CustomfieldFactory
     */
    public $customfieldFactory;

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
        CustomfieldFactory $customfieldFactory,
        Context $context
    ) {
    
        $this->coreRegistry = $registry;
        $this->customfieldFactory = $customfieldFactory;
        parent::__construct($context);
    }

    /**
     * @return \Softnoesis\Rma\Model\Statues
     */
    public function initStatues()
    {
        $customfieldId  = (int) $this->getRequest()->getParam('id');
        $customfield = $this->customfieldFactory->create();
        if ($customfieldId) {
            $customfield->load($customfieldId);
        }
        $this->coreRegistry->register('rma_customfield', $customfield);
        return $customfield;
    }

    public function filterData($data)
    {
        return $data;
    }
}
