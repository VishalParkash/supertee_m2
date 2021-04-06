<?php
namespace Forms\Registration\Block\Index;
 
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductFactory;
 
class View extends Template
{
    protected $_productloader;  
    protected $_productCollectionFactory;
    protected $_productRepository;

  public function __construct(
      Template\Context $context, 
      \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
      \Magento\Catalog\Model\ProductRepository $productRepository, 
      ProductFactory $ProductFactory,
          \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportCollectionFactory,   
    \Magento\Store\Model\StoreManagerInterface $storeManager,

      array $data = [])
    {
      $this->_productloader = $ProductFactory;
      $this->_productCollectionFactory = $productCollectionFactory;
      $this->_productRepository = $productRepository;
       $this->reportCollectionFactory = $reportCollectionFactory;
        $this->storeManager = $storeManager;

        parent::__construct($context, $data);
    }
 
  protected function _prepareLayout()
    {
        return parent::_prepareLayout(); // TODO: Change the autogenerated stub
    }

    public function getAllProductImages($product_id){
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      return $productimages = $product->getMediaGalleryImages();
    }
 
  public function getProducts($productId)
  {
    $collection = $this->_productCollectionFactory->create();
 $collection->addAttributeToSelect('*');   //semicolon is missing                  
 $collection->addFieldToFilter('entity_id', ['in' => $productId]);
 return $collection;

   // return $this->_productloader->create()->load($productId);
    // if(!empty($productId)){
    //   $productsList = array();
    //   foreach($productId as $products){
    //     $productsList[]  = $this->_productloader->create()->load($products);
    //   }
    // }
  }

  public function getPriceById($id)
{
    //$id = '21'; //Product ID
    $product = $this->_productloader->create();
    $productPriceById = $product->load($id)->getPrice();
    return $productPriceById;
}

public function getProductImgById($productId){
        $store = $this->_storeManager->getStore();
        // $productId = $productId;
        $product = $this->_productRepository->getById($productId);
 
        $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $productUrl = $product->getProductUrl();
        return $productImageUrl;
    }

    public function getProductUrl($productId){
        $store = $this->_storeManager->getStore();
        // $productId = $productId;
        return $product = $this->_productRepository->getById($productId)->getProductUrl();
 
        $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $productUrl = $product->getProductUrl();
        return $productImageUrl;
    }

    public function getMostViewedProducts()
{
      $storeId =  $this->storeManager->getStore()->getId();
      $collection = $this->reportCollectionFactory->create()
            ->addAttributeToSelect(
                '*'
            )->addViewsCount()->setStoreId(
                    $storeId
            )->addStoreFilter(
                    $storeId
            );
      $items = $collection->getItems();
      foreach ($items as $_product) {
        echo $product->getName() . ' - ' . $product->getProductUrl() . '<br />';
    }
    die();
     // return $items;
}

}