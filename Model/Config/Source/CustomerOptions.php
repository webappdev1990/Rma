<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Model\Config\Source;

class CustomerOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $customerCollectionFactory;
    protected $_options;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    public function getAllOptions()
    {
        $collection = $this->customerCollectionFactory->create();
        $this->_options = [];
        foreach ($collection as $driver) {
            $this->_options[] = [
                'label' => $driver->getFirstname() . " " . $driver->getLastname(),
                'value' => $driver->getEntityId()
            ];
        }
        return $this->_options;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->getAllOptions();
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
        $options = self::getAllOptions();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}
