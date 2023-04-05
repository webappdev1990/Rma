<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller\Image;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Framework\App\Action\Action
{
    /**
     * Image uploader
     *
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * Upload constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Softnoesis\Rma\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
      //  print_r($_f)
        try {
            $this->imageUploader->setBaseTmpPath('rma/customer/images');
            $this->imageUploader->setBasePath('rma/customer/images');
            $this->imageUploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $result = $this->imageUploader->saveFileToTmpDir('image');
            
//            $result['cookie'] = [
//                'name' => $this->_getSession()->getName(),
//                'value' => $this->_getSession()->getSessionId(),
//                'lifetime' => $this->_getSession()->getCookieLifetime(),
//                'path' => $this->_getSession()->getCookiePath(),
//                'domain' => $this->_getSession()->getCookieDomain(),
//            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
