<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Conditions;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Softnoesis\Rma\Model\ConditionsFactory;
use Softnoesis\Rma\Controller\Adminhtml\Conditions as ConditionsController;
use Magento\Framework\Registry;

class InlineEdit extends ConditionsController
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
        ConditionsFactory $conditionsFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($registry, $conditionsFactory, $context);
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
        
        foreach (array_keys($postItems) as $conditionsId) {
            $conditions = $this->dataLoad($conditionsId);
            try {
                $conditionsData = $this->filterData($postItems[$conditionsId]);
                $conditions->addData($conditionsData);
                $this->dataSave($conditions);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithConditionsId($conditions, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithConditionsId($conditions, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithConditionsId(
                    $conditions,
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
     * Add conditions id to error message
     *
     * @param Conditions $conditions
     * @param string $errorText
     * @return string
     */
    private function getErrorWithConditionsId($conditions, $errorText)
    {
        return '[Status ID: ' . $conditions->getId() . '] ' . $errorText;
    }
    
    private function dataSave($conditions)
    {
        $conditions->save();
    }
    
    private function dataLoad($conditionsId)
    {
        return $this->conditionsFactory->create()->load($conditionsId);
    }
}
