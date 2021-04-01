<?php

namespace Forms\Registration\Block\Adminhtml\Product;
use \Magento\Catalog\Api\ProductRepositoryInterfaceFactory;

class Custom extends \Magento\Backend\Block\Template
{
    protected $_registry;
    protected $request;
 
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;
        $this->request = $request;
        parent::__construct($context);
    }

    public function getCurrentProduct()
    {
        return $this->request->getParams();
        // return $this->_registry->registry('current_product');
    }
// protected $_productloader;
// protected $_storeManager;
 
// public function __construct(
//     \Magento\Framework\View\Element\Template\Context $context,
//     \Magento\Customer\Model\Session $customerSession,  
//     \Magento\Framework\ObjectManagerInterface $objectManager,
//     array $data = []
//  ) {
//     parent::__construct($context, $data);
//     $this->customerSession = $customerSession;
//     $this->_objectManager = $objectManager;
//   }

// Public function getProductImageUsingCode()
// {        
//      $productId = $this->request->getParam('id');
//          $store = $this->_storeManager->getStore();
//          // $productId = 95;
//          $product = $this->productrepository->getById($productId);
 
//          $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
//          $productUrl = $product->getProductUrl();
//          return $productUrl;
// }
}