<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Config\Source;
 
class OrderStatus implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     */
    protected $statusCollectionFactory;
 
    /**
     * Constructor
     *
     * @param \Softnoesis\Rma\Model\Faq $faq
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->statusCollectionFactory->create()->toOptionArray();
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
