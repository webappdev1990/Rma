<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Source;

class Inputtype implements \Magento\Framework\Data\OptionSourceInterface
{
    

    /**
     * Get product input types as option array
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function toOptionArray()
    {
        $inputTypes = [
            ['value' => 'text', 'label' => __('Text Field')],
//            ['value' => 'date', 'label' => __('Date')],
            ['value' => 'boolean', 'label' => __('Yes/No')],
            ['value' => 'select', 'label' => __('Dropdown')],
            ['value' => 'checkbox', 'label' => __('Checkbox')],
        ];
        return $inputTypes;
    }
}
