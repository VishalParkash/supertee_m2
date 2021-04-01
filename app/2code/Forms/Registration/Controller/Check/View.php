<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forms\Registration\Controller\Check;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var ScopeConfigInterface
     */

    protected $request;

    protected $connection;


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
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->session = $session;
        $this->connection = $resource->getConnection();
        
    }

    public function execute(){

        $post = (array) $this->getRequest()->getPost();

        $setSession = $post['setSession'];

        if($setSession == 1){
            $this->session->setData("setSession", $setSession);
        }elseif ($setSession == 2) {
            if($this->session->getData("setSession") == 1){
                $this->session->unsetData('setSession');    
            }
        }

        // echo $this->session->getData("setSession");die;
        $response = true;
        
        // if(!empty($pincode_data)){
        //     $response =  true;
        // }else{
        //     $response = false;
        // }


        // if(!empty($result)){
        //     $response['status']  = true;
        //     $response['msg']  = "success";
        //     $response['result']  = $imageUploaded;
        // } 
            
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
        }
        
    

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
}

}