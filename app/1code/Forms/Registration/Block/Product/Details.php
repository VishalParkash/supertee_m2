<?php
namespace Forms\Registration\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\View;
use Magento\Framework\Registry;
use Develodesign\Designer\Block\Product;
use Magento\Catalog\Helper\Data;
use Magento\Framework\App\ResourceConnection;


class Details extends Template
{

    /**
     * @var CurrentProduct
     */
    // private $_currentProduct;
    protected $_registry;
    protected $_productRepository;
    protected $_productloader;
    protected $_url; 
    protected $_storeManager;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Framework\UrlInterface $url,
        Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storemanager,
        ResourceConnection $resource,

        array $data = [])
    {
        //$this->_currentProduct  = $currentProduct;
        $this->_registry = $registry;
         $this->_productloader = $_productloader;
        $this->_productRepository = $productRepository;
        $this->swatchHelper = $swatchHelper;
        $this->helper = $helper;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_url = $url;
        $this->_storeManager =  $storemanager;
        $this->connection = $resource->getConnection();

        parent::__construct($context, $data);
    }

    public function getCurrentProductId(){
		$product = $this->getCurrentProduct();
		return $product->getId();
	}

	public function customProductUrl($id){
		$product = $this->getCurrentProduct();
		$id =  $product->getId();
        return $customProductUrl = $this->_url->getUrl().'details/product/customise/id/'.$id;
    }

    public function productCustomiserUrl($productId){
        return $customProductUrl = $this->_url->getUrl().'custom/product/customizer/id/'.$productId;
    }

	public function getProductById($id){
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		return $product = $objectManager->create('Magento\Catalog\Model\Product')->load($id);
	}

	public function getAllProductImagesById($product_id){
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      return $productimages = $product->getMediaGalleryImages();
    }

    public function getProductCollection(){
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
      $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->load($this->getCurrentProductId());
      return $collection;
    }
    public function getColorAttribute(){
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$atrributesRepository  = $objectManager->create('\Magento\Catalog\Model\Product\Attribute\Repository');
return $selectOptions = $atrributesRepository->get('Color')->getOptions();

    }

	public function getLoadProduct()
    {
    	$product = $this->getCurrentProduct();
		  $id =  $product->getId();
      return $this->_productloader->create()->load($id);
    }

     public function getCurrentProduct()
    {       
      return $this->_registry->registry('current_product');
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    public function getTierPrice(){
      $product = $this->getCurrentProduct();
      $id =  $product->getId();
//       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
// $product_obj = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface')->getById($id);                
// $tier_price = $product_obj->getTierPrice();
// echo "<pre>";print_r($tier_price);die;


      $getTierPrice =  $product->getTierPrice();
      if(!empty($getTierPrice)){
        return $getTierPrice;
      }
    }

    public function getProductType(){
      $product = $this->getCurrentProduct();
      $getTypeId =  $product->getTypeId();
      if(!empty($getTypeId)){
        return $getTypeId;
      }
    }

    public function getProductName(){
      $product = $this->getCurrentProduct();
      $getName =  $product->getName();
      if(!empty($getName)){
        return $getName;
      }
    }

    public function getShortDescription(){
      $product = $this->getCurrentProduct();
      $getShortDescription =  $product->getShortDescription();
      if(!empty($getShortDescription)){
        return $getShortDescription;
      }
    }

    public function getProductUrl(){
      $product = $this->getCurrentProduct();
      $getProductUrl =  $product->getProductUrl();
      if(!empty($getProductUrl)){
        return $getProductUrl;
      }
    }

    public function getSpecialPrice(){
      $product = $this->getCurrentProduct();
      $getSpecialPrice =  $product->getSpecialPrice();
      if(!empty($getSpecialPrice)){
        return $getSpecialPrice;
      }
    }

    public function getPrice(){
      $product = $this->getCurrentProduct();
      $getPrice =  $product->getPrice();
      if(!empty($getPrice)){
        return $getPrice;
      }
    }


    public function getAllProductImages(){
      $product = $this->getCurrentProduct();
      $product_id = $product->getId();
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager->create('Magento\Catalog\Model\Product')->load($product_id);
      // echo "<pre>";print_r($productimages = $product->getMediaGalleryImages());die;
      return $productimages = $product->getMediaGalleryImages();

      
    }

    public function getProductImage(){
		$product = $this->getCurrentProduct();
		$product_id = $product->getId();
		$objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
		$productimages = array();
		$product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      	$productimages = $product->getMediaGalleryImages();
      foreach($productimages as $productimage){ 
        return $productimage['url'];
      }
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getProductImgById($productId){
        $store = $this->_storeManager->getStore();
        // $productId = $productId;
        $product = $this->_productRepository->getById($productId);
 
        $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$product->getImage();
         $productUrl = $product->getProductUrl();
        return $productImageUrl;
    }

    public function getCordinates($productId){
        $productCoordinates = $this->connection->getTableName('productCoordinates');   
        $productCoordinatesData = $this->connection->fetchAll("SELECT * FROM ".$productCoordinates." WHERE product_id=".$productId);

        if(!empty($productCoordinatesData)){
            return $productCoordinatesData;
        }else{
          return false;
        }
    }

    public function getSuperAttributeData($productId)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->_productRepository->getById($productId);
        if ($product->getTypeId() != \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        }
 
        /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeInstance */
        $productTypeInstance = $product->getTypeInstance();
        $productTypeInstance->setStoreFilter($product->getStoreId(), $product);
 
        $attributes = $productTypeInstance->getConfigurableAttributes($product);
        $superAttributeList = [];
        foreach($attributes as $_attribute){
            $attributeCode = $_attribute->getProductAttribute()->getAttributeCode();;
            $superAttributeList[$_attribute->getAttributeId()] = $attributeCode;
        }
        return $superAttributeList;
    }

//     public function getProduct(){
//     if(is_null($this->_product)){
//         $this->_product = $this->helper->getProduct();
//     }
//     return $this->_product;
// }

}