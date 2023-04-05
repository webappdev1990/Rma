<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;

class CustomerName extends \Magento\Ui\Component\Listing\Columns\Column
{

    const NAME = 'customer_id';
    public $_storeManager;
    /**
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param StoreManagerInterface $storemanager
     * @param array                 $components
     * @param array                 $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storemanager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_storeManager = $storemanager;
    }
    
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as & $item) {
                $name = '';
                if (!empty($item['customer_id'])) {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $customer = $objectManager->get('\Magento\Customer\Model\Customer')->load($item['customer_id']);
                    $item[$fieldName] = $customer->getFirstname(). ' ' . $customer->getLastname();
                } else {
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $order = $objectManager->get('\Magento\Sales\Model\Order')->load($item['order_id']);
                    $item[$fieldName] = $order->getBillingAddress()->getName();
                }
            }
        }
        return $dataSource;
    }
}
