<?php

namespace User\Frontend\Block\Client;

// class Index extends BaseBlock
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
// use Magento\Framework\View\Element\Template;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_orderCollectionFactory;
    protected $orderRepository;
    protected $_customerSession;
    protected $redirect;
    protected $_categoryCollectionFactory;
    protected $_collectionFactory;
    protected $product;
    protected $_cartHelper;
    protected $_imageHelper;
    protected $_resourceFactory;

    public function __construct(
        // Template\Context $context,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\UrlInterface $url,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $collectionFactory,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        \Magento\Catalog\Model\Product $product,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->_customerSession = $customerSession;
        $this->redirect = $redirect;
        $this->_collectionFactory = $collectionFactory;
        $this->_url = $url;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_resourceFactory = $resourceFactory;
        $this->product = $product;
        $this->_imageHelper = $context->getImageHelper();
        $this->_cartHelper = $context->getCartHelper();
        parent::__construct($context, $data);
    }

    public function getLoggedinCustomerId() 
    {
        if ($this->_customerSession->isLoggedIn()) {
            return $this->_customerSession->getId();
        }
        $CustomRedirectionUrl = $this->_url->getUrl().'/';
        $this->redirect->setRedirect($CustomRedirectionUrl);
        return;

    }
    public function getOrderList($cust_id)
    {
        $order_collection = $this->_orderCollectionFactory->create();
        $order_collection->addFieldToFilter('customer_id', $cust_id);
        $order_collection->setOrder('created_at', 'desc');
        $order_collection->addAttributeToSelect('*');

        return $order_collection;
    }
    public function getOrderItems($order_id)
    {
        return $this->orderRepository->get($order_id);
    }

    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');        
        
        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }
                
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }
        
        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
        
        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }    
        
        return $collection;
    }

    public function getBestSellerCollection() {
        $bestSellerProdcutCollection = $this->_collectionFactory->create()->setModel('Magento\Catalog\Model\Product')->setPeriod('month');        
        return $bestSellerProdcutCollection;
    }

    public function imageHelperObj(){
        return $this->_imageHelper;
    }
    public function getProduct($id){
        return $this->product->load($id);
    }

    public function getBestsellerProduct(){
        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');
        $resourceCollection->setPageSize(10);
        return $resourceCollection;
    }

    public function getAddToCartUrl($product, $additional = [])
    {
    return $this->_cartHelper->getAddUrl($product, $additional);
    }
}
