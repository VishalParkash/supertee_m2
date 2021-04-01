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
class Upload extends \Magento\Framework\App\Action\Action
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
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
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
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        
    }

    public function execute(){
        // $i = 0;
        $files = $this->getRequest()->getFiles();
        $customiserImages = $files;
        $getFiles = $customiserImages['files'];
        // echo "count ".count($customiserImages);
        // echo "<pre>";print_r($customiserImages);
        // echo "<pre>";print_r($customiserImages['files']);
        // echo "count fiels ".count($customiserImages['files']);
        $targetDirectory = '/tempUpload/';
        $imageUploaded = array();
        // foreach($customiserImages['files'] as $Images){
        for($i=0;$i<count($getFiles); $i++){



            // echo "<pre>";print_r($getFiles[$i]);
            // echo "<pre>";print_r($Images[$i]);
            try{
                $fileName = time()."_".str_replace(array(':', '+', '/', '*', ' ','-'), "_", $getFiles[$i]['name']);

                $target = $this->_mediaDirectory->getAbsolutePath($targetDirectory);
                /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
                $uploader = $this->_fileUploaderFactory->create(['fileId' => $getFiles[$i]]); //Since in this example the input controller name is 'profileAdd', it shoud be used here
                /** Allowed extension types */
                $uploader->setAllowedExtensions(['jpg', 'png']);
                /** rename file name if already exists */
                $uploader->setAllowRenameFiles(true);
                /** upload file in folder "mycustomfolder" */
                $result = $uploader->save($target, $fileName);
                // echo $result['file'];
                $imageUploaded[]  = $fileName;
            }catch (\Exception $e) {
                $response['status']  = false;
                $response['msg']  = $e->getMessage();
            }
            // $i++;
        }

        if(!empty($result)){
            $response['status']  = true;
            $response['msg']  = "success";
            $response['result']  = $imageUploaded;
        } 
            
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
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