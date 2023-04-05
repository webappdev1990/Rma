<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Config\Source;
 
class Resolutions implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @var \Softnoesis\Rma\Model\Resolutions
     */
    public $resolutions;
 
    /**
     * Constructor
     *
     * @param \Softnoesis\Rma\Model\Faq $faq
     */
    public function __construct(\Softnoesis\Rma\Model\ResolutionsFactory $resolutionsFactory)
    {
        $this->resolutionsFactory = $resolutionsFactory;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->resolutionsFactory->create()->getCollection();
        foreach ($availableOptions as $option) {
            $options[] = [
                'label' => $option->getTitle(),
                'value' => $option->getResolutionId(),
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
