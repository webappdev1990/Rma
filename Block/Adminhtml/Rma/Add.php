<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma;

class Add extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_rma';
        $this->_mode = 'create';
        $this->_blockGroup = 'Softnoesis_Rma';

        parent::_construct();

        $this->setId('Softnoesis_Rma_rma_create');
        $this->removeButton('save');
        $this->removeButton('reset');
    }

    /**
     * Get header html
     *
     * @return string
     */
    public function getHeaderHtml()
    {
        return $this->getLayout()->createBlock('Softnoesis\Rma\Block\Adminhtml\Rma\Add\Header')->toHtml();
    }
}
