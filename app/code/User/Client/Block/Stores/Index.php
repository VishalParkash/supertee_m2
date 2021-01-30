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


class Index extends Template
{
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $_customerSession;
    protected $redirect;
    protected $_bestSellersCollectionFactory;


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
        parent::__construct($context, $data);
    }

    public function getLoggedinCustomerId() 
    {
        // echo "string";
        // echo "custo id".$this->_customerSession->getId();die;

        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getId();
        }
        $CustomRedirectionUrl = $this->_url->getUrl();
        $this->redirect->setRedirect($CustomRedirectionUrl);
        return;

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
}