<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Config\Source;
 
class Status implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var \Softnoesis\Rma\Model\Status
     */
    public $status;
 
    /**
     * Constructor
     *
     * @param \Softnoesis\Rma\Model\Faq $faq
     */
    public function __construct(\Softnoesis\Rma\Model\StatusesFactory $statusFactory)
    {
        $this->statusFactory = $statusFactory;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $availableOptions = $this->statusFactory->create()->getCollection()
            ->addFieldToFilter(
                'is_active',
                ['eq'=>\Softnoesis\Rma\Model\Statuses::IS_ACTIVE_ENABLE]
            )
            ->setOrder(
                'sort_order',
                'ASC'
            );
        foreach ($availableOptions as $option) {
            $options[] = [
                'label' => $option->getTitle(),
                'value' => $option->getStatusId(),
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
        $options = self::toOptionArray();
        foreach ($options as $option) {
            if ($option['value'] == $optionId) {
                return isset($option['label']) ? $option['label'] : null;
            }
        }
        return null;
    }
}
