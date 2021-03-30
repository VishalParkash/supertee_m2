<?php 
namespace Forms\Registration\Controller\Order; 
 
use \Magento\Framework\App\Action\Action;
 
 class Index extends \Magento\Framework\App\Action\Action 
 { 
 //Var $orderMgt : Order Management 
 protected $orderMgt; 

 //Var $messageManager : Message Manager 
protected $messageManager; 
protected $_redirect;
protected $_url;
 
 public function __construct( \Magento\Framework\App\Action\Context $context, \Magento\Sales\Api\OrderManagementInterface $orderMgt, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect) { 
 
 $this->orderMgt = $orderMgt; 
 $this->messageManager = $messageManager; 
 $this->_url = $url;
 $this->_redirect = $redirect; 
 parent::__construct($context); 
 } 

 public function execute() 
 { 
 // Initiate Object Manager 
 $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 

 // Fetch Logged in User Session 
 $customerSession = $objectManager->get('Magento\Customer\Model\Session'); 

 // Check is User Logged in or Not 
 if(!$customerSession->isLoggedIn()) { $this->_redirect('/'); die; } 

 // Get request parameters 
 $cid = $customerSession->getCustomer()->getId(); 

 $oid = $this->getRequest()->getParam('order_id'); 
 // Get request parameters 

 $order = $objectManager->create('Magento\Sales\Model\Order')->load($oid); 
 // Fetch Order Data 

 $orderdata = $order->getData(); 
 // echo "<pre>";print_r();die;
 // Get Order Status 

$orderTime = strtotime($orderdata['created_at']);
// $timefromdatabase = 1489834968;




 $order_status = $orderdata["status"]; 
 if(($order_status == 'Canceled') || ($order_status == 'canceled') ){
 	die('The order is already in a canceled state');
 }
 // echo "<pre>";print_r($order_status);die;
 // Get Customer Id of Order 

 $cus_id = $orderdata["customer_id"]; 
 // Check if logged in user & Order customer is same or not 


 	try {
 		$dif = time() - $orderTime;
		if($dif > 86400){
			die('Sorry, you cannot cancel your order right now');
		}else{
			if($this->orderMgt->cancel($oid)){
				die('You canceled the order successfully.');	
			}
			
		}   
    } catch (\Exception $e) {
    	  die("Sorry, We cant cancel your order right now.");  
    }

    $CustomRedirectionUrl = $this->_url->getUrl().'sales/order/history';
            // $this->_redirect->setRedirect($CustomRedirectionUrl);
            header("Location: ".$CustomRedirectionUrl);
            die;
            // return;


 // Check Order Status 

 // if($order_status == "pending") 
 // { 
 // $this->orderMgt->cancel($oid); 
 // die("Your order has been cancelled successfully.");
 // $this->messageManager->addSuccess("Your order has been cancelled successfully.") ; 
 // } 
 // else
 // { 
 // 	die("Sorry, We cant cancel your order right now.");
 // $this->messageManager->addError("Sorry, We cant cancel your order right now.") ; 
 // } 
 } 
 } 