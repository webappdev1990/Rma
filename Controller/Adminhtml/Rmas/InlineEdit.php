<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Adminhtml\Rmas;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Softnoesis\Rma\Model\RmaFactory;
use Softnoesis\Rma\Controller\Adminhtml\Rma as RmaController;
use Magento\Framework\Registry;

class InlineEdit extends RmaController
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
        RmaFactory $rmasFactory,
        Context $context
    ) {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($registry, $rmasFactory, $context);
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
        
        foreach (array_keys($postItems) as $rmasId) {
            $rmas = $this->dataLoad($rmasId);
            try {
                $rmasData = $this->filterData($postItems[$rmasId]);
                $rmas->addData($rmasData);
                $this->dataSave($rmas);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithRmaId($rmas, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithRmaId($rmas, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithRmaId(
                    $rmas,
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
     * Add rmas id to error message
     *
     * @param Rma $rmas
     * @param string $errorText
     * @return string
     */
    private function getErrorWithRmaId($rmas, $errorText)
    {
        return '[Rma ID: ' . $rmas->getId() . '] ' . $errorText;
    }
    
    private function dataSave($rmas)
    {
        $rmas->save();
    }
    
    private function dataLoad($rmasId)
    {
        return $this->rmasFactory->create()->load($rmasId);
    }
}
