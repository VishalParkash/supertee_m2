<?php
namespace Forms\Registration\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\View;
use Magento\Framework\Registry;
use Develodesign\Designer\Block\Product;


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

    public function __construct(
        Template\Context $context,
        Registry $registry,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Framework\UrlInterface $url,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,

        array $data = [])
    {
        //$this->_currentProduct  = $currentProduct;
        $this->_registry = $registry;
         $this->_productloader = $_productloader;
        $this->_productRepository = $productRepository;
        $this->swatchHelper = $swatchHelper;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_url = $url;

        parent::__construct($context, $data);
    }

    public function getCurrentProductId(){
		$product = $this->getCurrentProduct();
		return $product->getId();
		// return $product->getSku();

		// $productData = $this->_productRepository->get($product->getSku());
		// return $attributes = $productData->getAttributes();
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


//     public function attributesfnc(){
//     	$product_id = 96;
//     	$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
// $productObj = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id); 
// $colorOption = $productObj->getResource()->getAttribute('color')->getFrontend()->getValue($productObj); 
// $colorOptionId = $productObj->getResource()->getAttribute('color')->getOptionId($colorOption); 
// return $attr = $productObj->getResource()->getAttribute('color'); 
// $simpleProductHexCode = ''; 
// if ($attr->usesSource()) {
//  $attributeId = $attr->getSource()->getOptionId($colorOption); 
// $resource = $objectManager->get('Magento\Framework\App\ResourceConnection'); 
// $connection = $resource->getConnection(); 
// $tableName = $resource->getTableName('eav_attribute_option_swatch');
// $sql = "Select * FROM " . $tableName." where option_id = ".$attributeId; 
// $result_query = $connection->fetchAll($sql); 
// echo $productHexCode = $result_query[0]['value']; 
// }
//     }
    public function getColorAttribute(){
    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$atrributesRepository  = $objectManager->create('\Magento\Catalog\Model\Product\Attribute\Repository');
return $selectOptions = $atrributesRepository->get('Color')->getOptions();

    }

	public function getLoadProduct()
    {
    	$product = $this->getCurrentProduct();
		$id =  $product->getId();
		// $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
		// return $product = $objectmanager->create('Magento\Catalog\Model\Product')->load($id);
        return $this->_productloader->create()->load($id);
    }

     public function getCurrentProduct()
    {       

        return $this->_registry->registry('product');
    }


    public function getAllProductImages(){
      $product = $this->getCurrentProduct();
      $product_id = $product->getId();
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
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

}