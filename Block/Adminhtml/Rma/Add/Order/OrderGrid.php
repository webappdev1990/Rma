<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Block\Adminhtml\Rma\Add\Order;

class OrderGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    
    protected $_orderConfig;
    protected $moduleHelper;
    protected $_gridCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $gridCollectionFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Softnoesis\Rma\Helper\Data $moduleHelper,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $gridCollectionFactory,
        \Magento\Sales\Model\Order\Config $orderConfig,
        array $data = []
    ) {
        $this->_gridCollectionFactory = $gridCollectionFactory;
        $this->_orderConfig = $orderConfig;
        $this->moduleHelper = $moduleHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Block constructor
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('Softnoesis_Rma_rma_create_order_grid');
        $this->setDefaultSort('entity_id');
    }

    /**
     * Prepare grid collection object
     *
     * @return \Softnoesis\Rma\Block\Adminhtml\Rma\Create\Order\Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection \Magento\Sales\Model\ResourceModel\Order\Collection */
        $collection = $this->_gridCollectionFactory->create()
                ->setOrder('entity_id');

        if (!$this->moduleHelper->isAllowGuestEnabled()) {
            $collection->addFieldToFilter('customer_is_guest', 0);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'real_order_id',
            ['header' => __('Order'), 'width' => '80px', 'type' => 'text', 'index' => 'increment_id']
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                [
                    'header' => __('Purchase Point'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_view' => true,
                    'display_deleted' => true
                ]
            );
        }

        $this->addColumn(
            'created_at',
            ['header' => __('Purchased'), 'index' => 'created_at', 'type' => 'datetime']
        );

        $this->addColumn(
            'customer_email',
            [
                'header' => __('Customer Email'),
                'index' => 'customer_email',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'

            ]
        );

        $this->addColumn(
            'base_grand_total',
            [
                'header' => __('Grand Total (Base)'),
                'index' => 'base_grand_total',
                'type' => 'currency',
                'currency' => 'base_currency_code'
            ]
        );

        $this->addColumn(
            'grand_total',
            [
                'header' => __('Grand Total (Purchased)'),
                'index' => 'grand_total',
                'type' => 'currency',
                'currency' => 'order_currency_code'
            ]
        );

        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',
                'width' => '70px',
                'options' => $this->_orderConfig->getStatuses()
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Retrieve row url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('magerma/rmas/new', ['order_id' => $row->getId()]);
    }
}
