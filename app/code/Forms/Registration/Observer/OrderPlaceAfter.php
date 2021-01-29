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
        // echo "<pre>";print_r($order->getData());
        $storeId = $order->getData()['store_id'];
        $firstName = $order->getData()['customer_firstname'];
        $lastName = $order->getData()['customer_lastname'];
        $currencyCode = $order->getData()['base_currency_code'];
        $grandTotal = $order->getData()['base_grand_total'];

        $message = $firstName.$lastName." has placed and order of ".$currencyCode.$grandTotal;
        
        $customer = $this->customer;
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');

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


        $sql = "INSERT INTO " . $storeActivitiesTbl . "(store_id, custom_event, message, magento_event) VALUES (".$storeId.", 'orderPlaced', '".$message."' ,  'checkout_onepage_controller_success_action')";
                $this->connection->query($sql);


        // if ($order->getEntityId()) { // Order Id

        //     $items = $order->getItemsCollection();
        //     foreach($order->getItemsCollection() as $item){
        //     	$price = $item->getPrice();
        //     }
        // }
        // $price
        // die;
        $cartUrl = $this->_url->getUrl('/checkout/onepage/success/');
        $this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();            
        exit;
    }
}