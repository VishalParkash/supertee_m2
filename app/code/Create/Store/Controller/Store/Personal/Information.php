<?php
/**
 *
 * Copyright © 2015 Createcommerce. All rights reserved.
 */
namespace Create\Store\Controller\Store\Personal;
use Magento\Framework\Controller\Result\JsonFactory;

class Information extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $resultJsonFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {   
        $result = $this->resultJsonFactory->create();
        $data = $this->getRequest()->getPostValue();

        echo "<pre>";print_r($data);die;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();       
$question = $objectManager->create('Create\Store\Model\ClientPersonalInfo');
$question->setData($data);

$result->setData(['output' => true]);
                return $result;

        $this->resultPage = $this->resultPageFactory->create();  
		return $this->resultPage;
        
    }
}
