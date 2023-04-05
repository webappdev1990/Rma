<?php
/**
 * Softnoesis
 * Copyright(C) 04/2023 Softnoesis <ideveloper1990@gmail.com>
 * @package Softnoesis_Rma
 * @copyright Copyright(C) 2015 Softnoesis (ideveloper1990@gmail.com)
 * @author Softnoesis <ideveloper1990@gmail.com>
 */
namespace Softnoesis\Rma\Controller;

use Magento\Framework\App\RequestInterface;

abstract class Productlist extends \Magento\Framework\App\Action\Action
{
    /**
     * @var type
     */
    public $customer = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    public $customerSession;
    /**
     * @var Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     *
     * @return type
     */
    public function _getSession()
    {
        return $this->customerSession;
    }

    /**
     *
     * @param RequestInterface $request
     * @return type
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->_getSession()->authenticate()) {
            $this->_actionFlag->set('', 'no-dispatch', true);
        }
        return parent::dispatch($request);
    }
}
