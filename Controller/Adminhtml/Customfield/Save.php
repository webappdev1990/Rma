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
use Softnoesis\Rma\Controller\Adminhtml\Customfield as CustomfieldController;
use Magento\Framework\Serialize\SerializerInterface;
use Softnoesis\Rma\Model\CustomfieldFactory;
use Magento\Framework\Registry;

class Save extends CustomfieldController
{
    /**
     * statues factory
     *
     * @var CustomfieldFactory
     */
    public $customfieldFactory;

    /**
     * Core registry
     *
     * @var Registry
     */
    public $coreRegistry;
     /**
      * @var SerializerInterface
      */
    private $serializer;
    
    public function __construct(
        SerializerInterface $serializer,
        Registry $registry,
        CustomfieldFactory $customfieldFactory,
        Context $context
    ) {
        $this->serializer = $serializer;
        $this->coreRegistry = $registry;
        $this->customfieldFactory = $customfieldFactory;
        parent::__construct($registry, $customfieldFactory, $context);
    }
    
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $model = $this->initStatues();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['code_options_select']) && $data['frontend_input'] == 'select') {
                $dropdown = $data['code_options_select'];
                $dropdownOption = $this->getOptionValues($dropdown);
                $data['dropdown_option'] = $this->serializer->serialize($dropdownOption);
                unset($data['code_options_select']);
                unset($data['option']);
            }
            $model->setData($data);
            $this->_eventManager->dispatch(
                'rma_customfield_prepare_save',
                ['customfield' => $model, 'request' => $this->getRequest()]
            );
            $model->save();
            $this->messageManager->addSuccess(__('RMA Customfield Saved Successfully.'));
            $data = $this->_getSession()->getFormData(true);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
    
    public function getOptionValues($optionData)
    {
        $dropdownOption = [];
        foreach ($optionData as $options) {
            if (isset($options['delete'])) {
                continue;
            }
            $dropdownOption[] = $options;
        }
        return $dropdownOption;
    }
}
