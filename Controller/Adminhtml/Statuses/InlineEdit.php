<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */

namespace Softnoesis\Rma\Controller\Adminhtml\Statuses;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Softnoesis\Rma\Model\StatusesFactory;
use Softnoesis\Rma\Controller\Adminhtml\Statuses as StatusesController;
use Magento\Framework\Registry;

class InlineEdit extends StatusesController
{
    /**
     * @var JsonFactory
     */
    public $jsonFactory;

    /**
     * @param JsonFactory $jsonFactory
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        JsonFactory $jsonFactory,
        Registry $registry,
        StatusesFactory $statusesfactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($registry, $statusesfactory, $context);
    }
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        
        if (!($this->getRequest()->getParam('isAjax') && !empty($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        
        foreach (array_keys($postItems) as $statusId) {
            $status = $this->dataLoad($statusId);
            
            try {
                $statusData = $this->filterData($postItems[$statusId]);
                $status->addData($statusData);
                $this->dataSave($status);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithStatusId($status, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithStatusId($status, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithStatusId(
                    $status,
                    __('Something went wrong while saving the page.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
    /**
     * Add category id to error message
     *
     * @param Category $category
     * @param string $errorText
     * @return string
     */
    private function getErrorWithStatusId($status, $errorText)
    {
        return '[Status ID: ' . $status->getId() . '] ' . $errorText;
    }
    
    private function dataSave($status)
    {
        $status->save();
    }
    
    private function dataLoad($statusId)
    {
        return $this->statusesFactory->create()->load($statusId);
    }
}
