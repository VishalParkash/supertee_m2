<?php

namespace User\Client\Block\Marketing;

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

        // \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Message\Factory $messageFactory,
        \Magento\Framework\Message\CollectionFactory $collectionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager, 

        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->_customerSession = $customerSession;
        $this->redirect = $redirect;

        $this->messageFactory = $messageFactory;
        $this->collectionFactory = $collectionFactory;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;

        $this->_url = $url;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        ($this->messageManager->getMessages(true));       
        return parent::_prepareLayout();
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
}
