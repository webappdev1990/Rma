<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Source;
 
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{

    /**#@+
     * Product Status values
     */
    const IS_ACTIVE_ENABLE = 1;

    const IS_ACTIVE_DISABLED = 2;
    /**
     * @var \Softnoesis\Rma\Model\Statuses
     */
    public $statuses;
 
    /**
     * Constructor
     *
     * @param \Softnoesis\Rma\Model\Statuses $statuses
     */
    public function __construct(\Softnoesis\Rma\Model\Statuses $statuses)
    {
        $this->statuses = $statuses;
    }
    
    public static function getOptionArray()
    {
        return [
            self::IS_ACTIVE_ENABLE => __('Enabled'),
            self::IS_ACTIVE_DISABLED => __('Disabled')
        ];
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->statuses->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
