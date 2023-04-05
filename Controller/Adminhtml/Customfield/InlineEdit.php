<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Customfield;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Softnoesis\Rma\Model\CustomfieldFactory;
use Softnoesis\Rma\Controller\Adminhtml\Customfield as CustomfieldController;
use Magento\Framework\Registry;

class InlineEdit extends CustomfieldController
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
        CustomfieldFactory $customfieldFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($registry, $customfieldFactory, $context);
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
        
        foreach (array_keys($postItems) as $customfieldId) {
            $customfield = $this->dataLoad($customfieldId);
            try {
                $customfieldData = $this->filterData($postItems[$customfieldId]);
                $customfield->addData($customfieldData);
                $this->dataSave($customfield);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithCustomfieldId($customfield, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithCustomfieldId($customfield, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithCustomfieldId(
                    $customfield,
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
     * Add customfield id to error message
     *
     * @param Customfield $customfield
     * @param string $errorText
     * @return string
     */
    private function getErrorWithCustomfieldId($customfield, $errorText)
    {
        return '[Status ID: ' . $customfield->getId() . '] ' . $errorText;
    }
    
    private function dataSave($customfield)
    {
        $customfield->save();
    }
    
    private function dataLoad($customfieldId)
    {
        return $this->customfieldFactory->create()->load($customfieldId);
    }
}
