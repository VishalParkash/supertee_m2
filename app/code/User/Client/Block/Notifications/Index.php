<?php

namespace User\Client\Block\Notifications;

// class Index extends BaseBlock
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Framework\View\Element\Template;

class Index extends Template
{
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $_customerSession;
    protected $redirect;


    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->_customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->_url = $url;
        parent::__construct($context, $data);
    }

    public function getLoggedinCustomerId() 
    {
        // echo "string";
        // echo "custo id".$this->_customerSession->getId();die;

        if ($this->_customerSession->isLoggedIn()) {
            echo "string";die;
            return $this->_customerSession->getId();
        }
        $CustomRedirectionUrl = $this->_url->getUrl();
        $this->redirect->setRedirect($CustomRedirectionUrl);
        return;

    }
    public function getOrderList($store_id)
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

    public function getStores($userId){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        if(!empty($clientId)) {

            $themeTable = $connection->getTableName('store_clientpersonalinfo');
            $getStores = "SELECT * FROM " . $themeTable . " WHERE customer_id ='$userId'";
            return $result = $connection->fetchAll($getStores);

        }else{
            return false;
        }
    }

    
}