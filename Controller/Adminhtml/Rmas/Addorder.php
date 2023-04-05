<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

class Addorder extends \Softnoesis\Rma\Controller\Adminhtml\Rma
{
    /**
     * Choose Order action during new RMA creation
     *
     * @return void
     */
    public function execute()
    {
        $this->_initCreateModel();
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('New RMA'));
        $this->_view->renderLayout();
    }
}
