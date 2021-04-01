<?php
namespace Forms\Registration\Block\Product;
use Magento\Backend\Block\Template\Context;
use Forms\Registration\Model\DataExample;
use Magento\Catalog\Block\Product\ListProduct;
class Customizer extends \Magento\Framework\View\Element\Template
{
  protected $_resourceConnection;
  protected $registry;
	public function __construct(Context $context, 
    \Magento\Framework\App\ResourceConnection $resourceConnection,
    \Forms\Registration\Model\DataExampleFactory $DataExampleFactory,
    \Magento\Framework\App\RequestInterface $request,
    \Magento\Framework\Registry $registry,
    \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
    DataExample $DataExample,
    array $data = [])
    { 
        $this->DataExample = $DataExample;
        $this->DataExampleFactory = $DataExampleFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->request = $request;
        $this->listProductBlock = $listProductBlock;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }

    public function getProductCollection(){
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
      $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->load();
      return $collection;
    }
    public function getAddToCartPostParams($product){
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    public function getFormAction(){
        return $this->getUrl('postvendor/submit/submit', ['_secure' => true]);  
    }

    public function getProductImage($product_id){
      // $product = $this->getCurrentProduct();
      // $product_id = $product->getId();
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      // $product_id = $productId; //Replace with your product ID
      // $product_id = 98; //Replace with your product ID
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      $productimages = $product->getMediaGalleryImages();
      // echo "<pre>";print_r($productimages);die;
      foreach($productimages as $productimage){ 
        return $productimage['url'];
      }
    }



    public function getAllProductImages($product_id){
      // $product = $this->getCurrentProduct();
      // $product_id = $product->getId();
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      // $product_id = $productId; //Replace with your product ID
      // $product_id = 98; //Replace with your product ID
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      return $productimages = $product->getMediaGalleryImages();
      // echo "<pre>";print_r($productimages);die;
      // foreach($productimages as $productimage){ 
      //   return $productimage['url'];
      // }
    }

    public function getTaglines(){
      // $storeId = 39;
      $this->_connection = $this->_resourceConnection->getConnection();
      //$query = "SELECT firstTagline FROM data_example WHERE id = $storeId order by id DESC LIMIT 0,1"; 
      $query = "SELECT firstTagline FROM data_example order by id DESC LIMIT 0,1"; 
      $collection = $this->_connection->fetchAll($query);
      foreach($collection as $tagline){
        return $tagline['firstTagline'];
      }
      // return $collection;
    }

    // public function getTaglines(){
    //   $storeId = 39;
    //   $this->_connection = $this->_resourceConnection->getConnection();
    //   $query = "SELECT firstTagline FROM data_example WHERE id = $storeId order by id DESC LIMIT 0,1"; 
    //   $collection = $this->_connection->fetchAll($query);
    //   foreach($collection as $tagline){
    //     return $tagline['firstTagline'];
    //   }
    //   // return $collection;
    // }

    public function getCurrentProduct(){
    return $this->registry->registry('product');
  }

    public function getImagePath()
{
   $imagePath = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
   return $imagePath .'custom_templates/';
}

public function getCanvasUrl()
{
   return $this->getUrl('savecanvas/submit/savecanvas', ['_secure' => true]);
}

}

    ?>