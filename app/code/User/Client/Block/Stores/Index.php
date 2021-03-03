<?php

namespace User\Client\Block\Stores;

// class Index extends BaseBlock
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Stripe;



class Index extends Template
{
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $_customerSession;
    protected $redirect;
    protected $_bestSellersCollectionFactory;
    protected $_coreRegistry;

    protected $_productloader;
    protected $_storeManager;


    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
        ResourceConnection $resource,
        BestSellersCollectionFactory $bestSellersCollectionFactory,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Reports\Model\Grouped\CollectionFactory $collectionFactory,
        \Magento\Reports\Helper\Data $reportsData,
        DateTime $dateTime,
        \Magento\Framework\Registry $_coreRegistry,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Store\Model\StoreManagerInterface $storemanager,
        \Magento\Catalog\Model\ProductFactory $productFactory,


        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->_customerSession = $customerSession;
        $this->connection = $resource->getConnection();
        $this->redirect = $redirect;
        $this->_url = $url;
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_resourceFactory = $resourceFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_reportsData = $reportsData;
        $this->_coreRegistry = $_coreRegistry;

        $this->productrepository = $productrepository;
        $this->_storeManager =  $storemanager;

        $this->productFactory = $productFactory;

        parent::__construct($context, $data);
    }

    // public function getLoggedinCustomerId() 
    // {
    //     // echo "string";
    //     // echo "custo id".$this->_customerSession->getId();die;

    //     if ($this->_customerSession->isLoggedIn()) {
    //         return $this->_customerSession->getId();
    //     }
    //     $CustomRedirectionUrl = $this->_url->getUrl();
    //     $this->redirect->setRedirect($CustomRedirectionUrl);
    //     return;

    // }

    public function getProduct($storeId, $productId) {
        $product = $this->productFactory->create()
                            ->setStoreId($storeId)
                            ->load($productId);
        return $product;
    }
    public function getProductsCollection() {
        $productCollection = $this->productFactory->create()
                        ->getCollection()
                        ->addAttributeToSelect('*');
        return $productCollection;
    }

    public function getProductCollectionAsPerStore($storeId) {
        $productCollection = $this->productFactory->create()
                        ->setStoreId($storeId)
                        ->getCollection()
                        ->addAttributeToSelect('*');
        return $productCollection;
    }

    public function getFlashMessage(){
        return $this->_coreRegistry->registry('storeProfileResponse');die;
    }

    public function getStoreProfile($store_id){
        $storeProfileTbl = $this->connection->getTableName('store_clientpersonalinfo');
        $getActivities = "SELECT * FROM " . $storeProfileTbl . " WHERE storeId ='".$store_id."'";
        $result = $this->connection->fetchAll($getActivities);

        if(!empty($result)){
            return $result;
        }
        return false;

    }

    public function getStoreSetupInfo($store_id){
        $storeProfileTbl = $this->connection->getTableName('storeSetup_info');
        $getActivities = "SELECT * FROM " . $storeProfileTbl . " WHERE store_id ='".$store_id."' AND status='Published'";
        $result = $this->connection->fetchAll($getActivities);

        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public function getStoreSetupMiscInfo($store_id){
        $storeSetup_miscInfo = $this->connection->getTableName('storeSetup_miscInfo');
        $getStoreSetupMiscInfo = "SELECT * FROM " . $storeSetup_miscInfo . " WHERE store_id ='".$store_id."' AND status='Published'";
        $result = $this->connection->fetchAll($getStoreSetupMiscInfo);

        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public function getCurrentThemeName($theme_id){
        $theme_table = $this->connection->getTableName('theme');   
        $theme_data = $this->connection->fetchAll("SELECT * FROM ".$theme_table." WHERE theme_id=".$theme_id);

        if(!empty($theme_data)){
            foreach($theme_data as $theme){
                return $themeName = str_replace("/clientfrontend", "", $theme['theme_path']);
            }
        }
    }

    public function getThemesName($theme_id){
        $theme_table = $this->connection->getTableName('theme');   
        $theme_data = $this->connection->fetchAll("SELECT * FROM ".$theme_table." WHERE theme_id !=".$theme_id." AND theme_title = 'Client Frontend'");

        return $theme_data;
        // if(!empty($theme_data)){
        //     foreach($theme_data as $theme){
        //         return $themeName = str_replace("/clientfrontend", "", $theme['theme_path']);
        //     }
        // }
    }

    public function getOrderNumberByEmail($customerEmail, $store_id){

        $OrderCustomer_table = $this->connection->getTableName('OrderCustomer_table');
        $getCustomers = "SELECT count(customer_email)  FROM " . $OrderCustomer_table . " WHERE store_id ='".$store_id."' AND customer_email = '".$customerEmail."'";
        $result = $this->connection->fetchAll($getCustomers);

        if(!empty($result)){
            return $result;
        }
        return false;


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order_collection = $objectManager->create('Magento\Sales\Model\Order')->getCollection()->addAttributeToFilter('customer_email', $customerEmail);

        return ($order_collection->getData());
        // foreach ($order_collection as $order){ 
        //     echo "Order Id: ".$order->getEntityId(); 
        //     echo "Customer Id: ".$order->getCustomerId(); 
        // } 
    }

    public function getCustomers($store_id){
        $OrderCustomer_table = $this->connection->getTableName('OrderCustomer_table');
        // $getCustomers = "SELECT DISTINCT customer_email FROM " . $OrderCustomer_table . " WHERE store_id ='".$store_id."' ORDER BY id ASC";
        $getCustomers = "SELECT * FROM " . $OrderCustomer_table . " WHERE store_id ='".$store_id."' GROUP BY customer_email ORDER BY id ASC";
        $result = $this->connection->fetchAll($getCustomers);

        if(!empty($result)){
            return $result;
        }
        return false;
        
    }

    public function getOrders(){

        if (!$this->orders) {
            $this->orders = $this->_orderCollectionFactory->create()->addFieldToSelect('*')->addFieldToFilter('customer_id',$customerId)->setOrder('created_at','desc');
        }
        return $this->orders;
    }

    public function getStores($client_id){
        $storeProfileTbl = $this->connection->getTableName('store_clientpersonalinfo');
        $getStores = "SELECT * FROM " . $storeProfileTbl . " WHERE customer_id ='".$client_id."'";
        $result = $this->connection->fetchAll($getStores);

        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public function getStoreUrl($storeId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore($storeId)->getBaseUrl();
    }


    public function getStoreSetupData($store_id){
        $storeProfileTbl = $this->connection->getTableName('storeSetup_theme');
        $getActivities = "SELECT * FROM " . $storeProfileTbl . " WHERE store_id ='".$store_id."' ORDER BY id DESC LIMIT 1";
        $result = $this->connection->fetchAll($getActivities);

        if(!empty($result)){
            return $result;
        }
        return false;

    }


    public function getOrderListLimit($store_id)
    {

        $order_collection = $this->_orderCollectionFactory->create();
        $order_collection->addFieldToFilter('store_id', $store_id);
        $order_collection->setOrder('created_at', 'desc');
        $order_collection->setPageSize(5);
        $order_collection->addAttributeToSelect('*');

        return $order_collection;
    }

    public function getOrderListAll($store_id)
    {

        $order_collection = $this->_orderCollectionFactory->create();
        $order_collection->addFieldToFilter('store_id', $store_id);
        $order_collection->setOrder('created_at', 'desc');
        $order_collection->addAttributeToSelect('*');

        return $order_collection;
    }
    public function getOrderItems($order_id)
    {
        return $this->orderRepository->get($order_id);
    }

    public function getTransactionListAll($client_id){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        if(!empty($client_id)) {

            $themeTable = $connection->getTableName('supertee_client_payments');
            $getTransactions = "SELECT * FROM " . $themeTable . " WHERE client_id ='$client_id' ORDER BY id DESC";
            return $result = $connection->fetchAll($getTransactions);

        }else{
            return false;
        }
        // $objectModelManager = \Magento\Framework\App\ObjectManager::getInstance ();
        // $collection = $objectModelManager->get ( 'User\Client\Model\Payments' )->getCollection ()->addFieldToSelect ( '*' );
        // $collection->addFieldToFilter ( 'client_id', $client_id );
        
        /**
         * Set order for manage order
        //  */
        // $collection->setOrder ( 'id', 'desc' );
        // $this->setCollection ( $collection );
    }

    public function getTransactionListLimit($client_id){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        if(!empty($client_id)) {

            $themeTable = $connection->getTableName('supertee_client_payments');
            $getTransactions = "SELECT * FROM " . $themeTable . " WHERE client_id ='$client_id' ORDER BY id DESC LIMIT 0,5";
            return $result = $connection->fetchAll($getTransactions);

        }else{
            return false;
        }
    }

    public function getCommission($clientId){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        if(!empty($clientId)) {

            $themeTable = $connection->getTableName('store_clientpersonalinfo');
            $getCommission = "SELECT commision FROM " . $themeTable . " WHERE id ='$clientId'";
            return $result = $connection->fetchAll($getCommission);

        }else{
            return false;
        }
    }

    public function calculateCommissionAmt($commission, $amount){
        return $total = ($amount*($commission/100));
    }

    public function getActivities($store_id){
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');


        $getActivities = "SELECT * FROM " . $storeActivitiesTbl . " WHERE store_id ='".$store_id."'";
        return $result = $this->connection->fetchAll($getActivities);

    }

    public function filtersData($period, $storeId){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magento\Reports\Model\ResourceModel\Report\Collection\Factory'); 
        $collection = $productCollection->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection'); 

        $collection->setPeriod('month');
        //$collection->setPeriod('year');
        //$collection->setPeriod('day');

        foreach ($collection as $item) {
            print_r($item->getData());
        }
    }

    public function getProductCollection()
    {

        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
        $resourceCollection->setPageSize(3);
        return $resourceCollection;


        $productIds = [];
        $bestSellers = $this->_bestSellersCollectionFactory->create()
            ->setPeriod('month')->setPageSize(3);
        foreach ($bestSellers as $product) {
            $productIds[] = $product->getProductId();
        }
        $collection = $this->_productCollectionFactory->create()->addIdFilter($productIds);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addStoreFilter($this->getStoreId())->setPageSize($this->getProductsCount());
        return $collection;
    }

    public function getstripeUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl = $storeManager->getStore()->getBaseUrl();

        \Stripe\Stripe::setApiKey('sk_test_51HIGV6Jy1OZGyp65YiZU9LgNo89qrU2tmtM7N1ghE3VNzuRA8EBQnubPpngp972bhiwJ7u4zc7b3FwSeK3bFbsp0005qZjjNJa');
        $account = \Stripe\Account::create([
           'type' => 'express'
        ]);
        $account_links = \Stripe\AccountLink::create([
          'account' => $account->id,
          'refresh_url' => $baseUrl.'reauth/index/issue',
          'return_url' => $baseUrl.'return/index/success',
          'type' => 'account_onboarding'
        ]);
        return $this->getUrl($account_links->url);  
        //return 'Hello';  
    }


    public function getProductImgById($productId){
        $store = $this->_storeManager->getStore();
        // $productId = $productId;
        $product = $this->productrepository->getById($productId);
 
        $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $productUrl = $product->getProductUrl();
        return $productImageUrl;
    }

    public function getSVGcollection(){
        $supertee_svgCollections = $this->connection->getTableName('supertee_svgCollections');
        $getSVGcollection = "SELECT * FROM " . $supertee_svgCollections . " ORDER BY id DESC";
        $result = $this->connection->fetchAll($getSVGcollection);

        if(!empty($result)){
            return $result;
        }
        return false;
    }

    public function gtSVGbyId($svgId){
        $supertee_svgCollections = $this->connection->getTableName('supertee_svgCollections');
        $getSVGcollection = "SELECT * FROM " . $supertee_svgCollections . " WHERE id= ".$svgId;
        $result = $this->connection->fetchAll($getSVGcollection);

        if(!empty($result)){
            return $result;
        }
        return false;
    }
}