<?php 
namespace Forms\Registration\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;


class OrderPlaceAfter implements ObserverInterface {

	protected $_responseFactory;
    protected $_url;
    protected $customer;
    protected $connection;

    public function __construct(\Magento\Framework\App\ResponseFactory $responseFactory,
    	\Magento\Customer\Model\Session $customer,
    	ResourceConnection $resource,
		\Magento\Framework\UrlInterface $url)
    {
        $this->_responseFactory = $responseFactory;
        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();
        $this->customer = $customer;
        $this->_url = $url;
    }

    public function execute(Observer $observer) {

        $order 	= $observer->getEvent()->getOrder();
        // echo "<pre>";print_r($order->getData());die;
        $storeId = $order->getData()['store_id'];
        $firstName = $order->getData()['customer_firstname'];
        $lastName = $order->getData()['customer_lastname'];
        $customer_email = $order->getData()['customer_email'];
        $currencyCode = $order->getData()['base_currency_code'];
        $grandTotal = $order->getData()['base_grand_total'];

        // echo $customer_location = $order->getShippingAddress()->getData("region");
        // echo $customer_city = $order->getShippingAddress()->getData("city");
        // $billingaddress = $orderDetails->();
        $customer_location = ($order->getBillingAddress()->getData()['region']);
        $customer_address = ($order->getBillingAddress()->getData()['street']);
        $customer_city = ($order->getBillingAddress()->getData()['city']);
        $customer_postcode = ($order->getBillingAddress()->getData()['postcode']);
        $customer_telephone = ($order->getBillingAddress()->getData()['telephone']);
        // echo "<pre>";print_r($order->getShippingAddress()->getData());die;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currency = $objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode);
        $currencySymbol = $currency->getCurrencySymbol();

        $message = $firstName." ".$lastName." has placed and order of ".$currencySymbol.number_format($grandTotal, 2);
        
        $customer = $this->customer;
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');


        //"INSERT INTO ".$storeSetup_info."(client_id, customStore_id, store_id, storeCode, theme_id, updatesNotification, desktopNotification, pushNotification, status) VALUES ('".$getMyClientId."', '".$parent_id."', '".$storeId."', '".$storeCode."', '".$getTheme."', '".$post['updatesNotification']."', '".$post['desktopNotification']."', '".$post['pushNotification']."', '".$changeStatus."')";
                // $this->connection->query($InsertOrderCustomer_table);
                // $lastInsertedId = $this->connection->lastInsertId();

        $InsertOrderCustomer_table = "INSERT INTO OrderCustomer_table(customer_email, customer_firstname, customer_lastname, store_id, customer_phone, customer_location, customer_address, customer_city, customer_postcode, customer_registration_date) VALUES ('".$customer_email."', '".$firstName."', '".$lastName."', '".$storeId."', '".$customer_telephone."', '".$customer_location."', '".$customer_address."', '".$customer_city."', '".$customer_postcode."', NOW())";
            $this->connection->query($InsertOrderCustomer_table);

        // echo "<pre>";print_r($customer->getData());die;

        if(($customer->isLoggedIn())){
        	$customerId = $customer->getId();
        	$UserEmail = $customer->getCustomer()->getEmail();
			$orderId = $order->getId();
			// echo "<pre>";print_r($order->getGrandTotal());die;
			$price = $order->getGrandTotal();
			$donation_points = ($price*10);

			$themeTable = $this->connection->getTableName('referral_system');
			$themeTable2 = $this->connection->getTableName('user_rewards');
            

                $checkReferral = "SELECT * FROM " . $themeTable . " WHERE recipient_email ='".$UserEmail."' AND firstOrder_status = 0";
                $result = $this->connection->fetchAll($checkReferral);

                if(!empty($result)){
                    foreach($result as $collection){
                        $sql = "INSERT INTO " . $themeTable2 . " (customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$collection['user_id'].", 100, 'credit', 'firstOrder_reward')";
                        $this->connection->query($sql);

                        $sql2 = "UPDATE " . $themeTable . " SET firstOrder_status =1 WHERE recipient_email='$UserEmail'";
                        $this->connection->query($sql2);
                    }
                }

                $sql = "INSERT INTO " . $themeTable2 . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customerId.", ".$donation_points.", 'credit',  'user_purchase')";
                $this->connection->query($sql);
                // die;
        }else{
            $customer   = $observer->getEvent()->getCustomer();
        }


        $sql = "INSERT INTO " . $storeActivitiesTbl . "(order_id, store_id, custom_event, message, magento_event) VALUES ('".$order->getId()."' , ".$storeId.", 'orderPlaced', '".$message."' ,  'checkout_onepage_controller_success_action')";
                $this->connection->query($sql);


        // if ($order->getEntityId()) { // Order Id

        //     $items = $order->getItemsCollection();
        //     foreach($order->getItemsCollection() as $item){
        //     	$price = $item->getPrice();
        //     }
        // }
        // $price
        // die;
        // $cartUrl = $this->_url->getUrl('/checkout/onepage/success/');
        // $this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();            
        // exit;
    }
}