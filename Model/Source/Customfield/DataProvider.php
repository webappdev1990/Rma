<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Model\Source\Customfield;

use Softnoesis\Rma\Model\ResourceModel\Customfield\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\SerializerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Softnoesis\Rma\Model\ResourceModel\Customfield\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    private $serializer;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $codeCollectionFactory,
        DataPersistorInterface $dataPersistor,
        SerializerInterface $serializer,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $codeCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->serializer = $serializer;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $page \Softnoesis\Rma\Model\Customfield */
        foreach ($items as $page) {
            $dropdownValues = $page->getData('dropdown_option');
            if (isset($dropdownValues) && !is_null($dropdownValues)) {
                $page->setData('code_options_select', $this->serializer->unserialize($dropdownValues));
                unset($page['dropdown_option']);
            }
            $this->loadedData[$page->getId()] = $page->getData();
        }

        $data = $this->dataPersistor->get('rma_customfield');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('rma_customfield');
        }
        return $this->loadedData;
    }
}
