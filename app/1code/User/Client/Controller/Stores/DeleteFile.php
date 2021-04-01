<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace User\Client\Controller\Stores;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DeleteFile extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Session\Generic
     */
    protected $session;

    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;

    /**
     * @var \ManishJoy\CustomerLogin\Model\Customer $customerModel
     */
    protected $customerModel;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $request;

    protected $connection;

    protected $_fileUploaderFactory;
    protected $_mediaDirectory;
    
    protected $file;

    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Json\Helper\Data $helper,
        \ManishJoy\CustomerLogin\Model\Customer $customerModel,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Filesystem\Driver\File $file
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->helper = $helper;
        $this->customerModel = $customerModel;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;

        $this->connection = $resource->getConnection();
        $this->mathRandom = $mathRandom;
        $this->_fileUploaderFactory = $fileUploaderFactory;

        $this->_file = $file;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        
    }

    public function execute(){
        // $i = 0;
        // $files = $this->getRequest()->post();
        $files = $this->getRequest()->getPostValue();
        echo "<pre>";print_r($files);die;
        $target = $this->_mediaDirectory->getAbsolutePath($targetDirectory);

        $resultJson = $this->resultJsonFactory->create();
        

        if(empty($files)){
            $response['status']  = false;
            $response['msg']  = "empty data";
            return $resultJson->setData($response);
        }
        if ($this->_file->isExists($target . $files))  {
            if($this->_file->deleteFile($target . $files)){
                $response['status']  = true;
                $response['msg']  = "success";
                
            }else{
                $response['status']  = false;
                $response['msg']  = "error";
        }
        }else{
            $response['status']  = false;
            $response['msg']  = "invalid file name";
            
        }
        return $resultJson->setData($response);

        
        }
        
    

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
}

    public function getRandomString($length,  $chars = null)
    {
        return $this->mathRandom->getRandomString($length, $chars);
    }
}