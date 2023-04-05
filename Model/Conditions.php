<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model;

class Conditions extends \Magento\Framework\Model\AbstractModel
{

    const CONDITIONS_ID = 'conditions_id'; // We define the id fieldname
    
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
    public $eventObject = 'conditions'; // parent value is 'object'

    /**
     * Name of object id field
     *
     * @var string
     */
    public $idFieldName = self::CONDITIONS_ID;
    
    public function _construct()
    {
        $this->_init('Softnoesis\Rma\Model\ResourceModel\Conditions');
    }
    
    public function getAvailableStatuses()
    {
        return [self::IS_ACTIVE_ENABLE => __('Enabled'), self::IS_ACTIVE_DISABLED => __('Disabled')];
    }
}
