<?php
namespace Forms\Registration\Block\Product;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class CustomBtn extends Template{

	protected $registry;
	protected $_url;
	protected $_resourceConnection;

	public function __construct(
		Template\Context $context,
		Registry $registry,
		\Magento\Framework\App\ResourceConnection $resourceConnection,
		\Magento\Framework\UrlInterface $url,

		array $data = []){
		parent::__construct($context, $data);
		$this->_url = $url;
		$this->_resourceConnection = $resourceConnection;
		$this->registry = $registry;
	}

	public function getCurrentProductId(){
		$product = $this->getCurrentProduct();
		return $product->getId();
		// echo "<pre>";print_r($product->getId());
		// 	die;
		// foreach ($product as  $value) {
			
		// 	die;
		// }
	}
	public function customProductUrl($productId){
        return $customProductUrl = $this->_url->getUrl().'details/product/customise/id/'.$productId;
    }

    public function productCustomiserUrl($productId){
        return $customProductUrl = $this->_url->getUrl().'custom/product/customizer/id/'.$productId;
    }

    public function getTaglines(){
      $storeId = 39;
      $this->_connection = $this->_resourceConnection->getConnection();
      $query = "SELECT firstTagline FROM data_example WHERE id = $storeId order by id DESC LIMIT 0,1"; 
      $collection = $this->_connection->fetchAll($query);
      foreach($collection as $tagline){
        return $tagline['firstTagline'];
      }
      // return $collection;
    }

	public function getCurrentProduct(){
		return $this->registry->registry('product');
	}

	public function getProductImage(){
      // $product_id = $this->request->getParams()['id'];
		$product = $this->getCurrentProduct();
		$product_id = $product->getId();
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
}