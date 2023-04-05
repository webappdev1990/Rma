<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Reasons;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Softnoesis\Rma\Model\ReasonsFactory;
use Softnoesis\Rma\Controller\Adminhtml\Reasons as ReasonsController;
use Magento\Framework\Registry;

class InlineEdit extends ReasonsController
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
        ReasonsFactory $reasonsFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($registry, $reasonsFactory, $context);
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
        
        foreach (array_keys($postItems) as $reasonsId) {
            $reasons = $this->dataLoad($reasonsId);
            try {
                $reasonsData = $this->filterData($postItems[$reasonsId]);
                $reasons->addData($reasonsData);
                $this->dataSave($reasons);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithReasonsId($reasons, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithReasonsId($reasons, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithReasonsId(
                    $reasons,
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
    private function getErrorWithReasonsId($reasons, $errorText)
    {
        return '[Status ID: ' . $reasons->getId() . '] ' . $errorText;
    }
    
    private function dataSave($reasons)
    {
        $reasons->save();
    }
    
    private function dataLoad($reasonsId)
    {
        return $this->reasonsFactory->create()->load($reasonsId);
    }
}
