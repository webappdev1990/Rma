<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model;

class Customfield extends \Magento\Framework\Model\AbstractModel
{

    const CUSTOMFIELD_ID = 'customfield_id'; // We define the id fieldname
    
    const IS_ACTIVE_ENABLE = 1;
    const IS_ACTIVE_DISABLED = 2;
    
    /**
     * Prefix of model events names
     *
     * @var string
     */

    public $eventPrefix = 'rma'; // parent value is 'core_abstract'

    /**
     * Name of the event object
     *
     * @var string
     */
    public $eventObject = 'customfield'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    public $idFieldName = self::CUSTOMFIELD_ID;
    
    public function _construct()
    {
        $this->_init('Softnoesis\Rma\Model\ResourceModel\Customfield');
    }
    
    public function getAvailableStatuses()
    {
        return [self::IS_ACTIVE_ENABLE => __('Enabled'), self::IS_ACTIVE_DISABLED => __('Disabled')];
    }
}
