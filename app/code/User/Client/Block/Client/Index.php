<?php

namespace User\Client\Block\Client;

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


class Index extends Template
{
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $_customerSession;
    protected $redirect;
    protected $_bestSellersCollectionFactory;
    protected $_productRepositoryFactory;


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
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        DateTime $dateTime,

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
        $this->_productRepositoryFactory = $productRepositoryFactory;
        parent::__construct($context, $data);
    }

    public function getLoggedinCustomerId() 
    {
        // if($customerSession->getData('customer_id'))
        // {
        //     $customer = $this->_customerRepositoryInterface->getById($customerSession->getData('customer_id'));
        //     return $customer->getFirstname();
        // }

        // if ($this->_customerSession->isLoggedIn()) {
        //     die('sssee');
        //     return $this->_customerSession->getId();
        // }
        // $CustomRedirectionUrl = $this->_url->getUrl();
        // $this->redirect->setRedirect($CustomRedirectionUrl);
        // return;

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

    public function getTodayActivities($store_id){
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');
        $getActivities = "SELECT * FROM " . $storeActivitiesTbl . " WHERE store_id ='".$store_id."' AND (createAt >= CURDATE() AND createAt < CURDATE() + INTERVAL 1 DAY)";
        return $result = $this->connection->fetchAll($getActivities);
    }
    public function getYesterdayActivities($store_id){
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');
        $getActivities = "SELECT * FROM " . $storeActivitiesTbl . " WHERE store_id ='".$store_id."' AND (createAt BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE())";
        return $result = $this->connection->fetchAll($getActivities);
    }
    public function getRestActivities($store_id){
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');
        $getActivities = "SELECT * FROM " . $storeActivitiesTbl . " WHERE store_id ='".$store_id."' AND (createAt < CURDATE() - INTERVAL 1 DAY AND CURDATE())";
        return $result = $this->connection->fetchAll($getActivities);
    }

    public function getActivities($store_id){
        $storeActivitiesTbl = $this->connection->getTableName('storeActivities');
        $getActivities = "SELECT * FROM " . $storeActivitiesTbl . " WHERE store_id ='".$store_id."' order by id DESC";
        return $result = $this->connection->fetchAll($getActivities);
    }

    public function getNotifications($client_id){
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


    public function getOrderDetails($orderId){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);
    }

    public function getProductImage($product_id){
        $product = $this->_productRepositoryFactory->create()
    ->getById($product_id);

        $objectManager =\Magento\Framework\App\ObjectManager::getInstance();
        $helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');

        $imageUrl = $helperImport->init($product, 'product_page_image_small')
                        ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                        ->getUrl();
        return $imageUrl;
    }

    public function getStoresByClient($client_id){
        $StoreTable = $this->connection->getTableName('store_clientpersonalinfo');
        $getActivities = "SELECT * FROM " . $StoreTable . " WHERE customer_id ='".$client_id."'";
        $result = $this->connection->fetchAll($getActivities);

        if(!empty($result)){
            $stores = array();
            foreach($result as $store){
                $stores[] = $store;
            }
        }else{
            $stores = array();
        }
        return $stores;
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
}