<?php
/**
 *
 * Copyright © 2015 Usercommerce. All rights reserved.
 */
namespace User\Client\Controller\Marketing;
use \Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResourceConnection;

class Submit extends \Magento\Framework\App\Action\Action
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
    protected $_messageManager;

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
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ResourceConnection $resource,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
        $this->_messageManager = $messageManager;

        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();

    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {

    	$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    	try{

    	$post = (array) $this->getRequest()->getPost();

    	$themeTable = $this->connection->getTableName('clientMarketing');

    	if(empty($post['fullName']) || (empty($post['email'])) || (empty($post['company'])) ){
    		$response = 3;
    		$resultRedirect->setUrl($this->_redirect->getRefererUrl()."?res=".$response);
    		return $resultRedirect;
    	}
    	if(!empty($post['phone'])){
    		$phone = $post['phone'];
    	}else{
    		$phone = '';
    	}

    	if(empty($post['howToHelp'])){
    		$howToHelp = $post['howToHelp'];
    	}else{
    		$howToHelp = '';
    	}
    	if(empty($post['hearAboutUs'])){
    		$hearAboutUs = $post['hearAboutUs'];
    	}else{
    		$hearAboutUs = '';
    	}

    	$sql = "INSERT INTO " . $themeTable . "(fullName, email, company, phone, howToHelp, hearAboutUs) VALUES ('".$post['fullName']."', '".$post['email']."','".$post['company']."','".$post['phone']."','".$howToHelp."','".$hearAboutUs."' )";

    	
            if($this->connection->query($sql)){
            	$recipient_email = "vishal.parkash@cerebrent.com";
            	$referralUserEmail = $post['email'];
				$msg = '';
	            $msg .= "<p>Hi Admin</p>";
	            $msg .= "<p>You have recieved a request to market the client store</p>";
	            $msg .= "<p>The details of the client are as follow:</p>";
	            $msg .= "<p>Name: ".$post['fullName']."</p>";
	            $msg .= "<p>Email: ".$post['email']."</p>";
	            $msg .= "<p>Company: ".$post['company']."</p>";
	            $msg .= "<p>Phone: ".$phone."</p>";
	            $msg .= "<p>Message: ".$howToHelp."</p>";
	            $msg .= "<p>Heard from: ".$hearAboutUs."</p>";
	            
	            $msg .= "<p>Thanks & Regards</p>";
	            
	            $email = new \Zend_Mail();
	            $email->setSubject("You are referred by your friend to join Supertee");
	            $email->setBodyHtml($msg);
	            $email->setFrom($referralUserEmail, "Supertee");
	            $email->addTo($recipient_email, "Admin");
	            if($email->send()){
	            	$response = 1;
	            	$this->_messageManager->addSuccessMessage('Your request submitted successfully.');
	            }else{
	            	$response = 2;
	            	$this->_messageManager->addErrorMessage('Some error occured');
	            }
            }else{
            	$response = 2;
            }	
    	}catch (Exception $e) {
    			$response = 2;
                $this->_messageManager->addErrorMessage('We cannot process your request right now.');
            }

			
    	

    	// if (empty($post['fullName'])) {
     //        $this->_messageManager->addErrorMessage('Your Error Message');
     //    } else {
     //        $this->_messageManager->addSuccessMessage('Your Success Message');
     //    }
        
        $resultRedirect->setUrl($this->_redirect->getRefererUrl()."?res=".$response);

        return $resultRedirect;
        $this->resultPage = $this->resultPageFactory->create();  
		return $this->resultPage;
        
    }
}
