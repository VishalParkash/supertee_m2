<?php

namespace Forms\Registration\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Model\StockRegistry;
use Forms\Registration\Model\DataExample;
// use Zend\Form\Annotation\Instance;
use Zend\Form\Annotation\Instance;
// use Forms\Registration\Helper\Data;

// use Zend\Form\Annotation\Object as AnnotationObject;

/**
 * This class used to display the products collection
 */
class Manage extends \Magento\Framework\View\Element\Template {
    
    /**
     * Initilize variable for product factory
     *
     * @var \Magento\Catalog\Model\ProductFactory
     */

    protected $_resourceConnection;
    protected $_connection;
    protected $productFactory;
    protected $_currency;
    /**
     * Initilize variable for stock registry
     *
     * @var Magento\CatalogInventory\Model\StockRegistry
     */
    protected $stockRegistry;
    protected $messageManager;
    protected $_url;

    protected $_categoryCollectionFactory;
    protected $_categoryHelper;
    protected $_attributeFactory;
    protected $_productAttributeRepository;

    // protected $Data;
    // protected $DataExampleFactory;
    
    /**
     *
     * @param Template\Context $context            
     * @param ProductFactory $productFactory            
     * @param array $data            
     */
    public function __construct(Template\Context $context, Collection $productFactory, \Magento\Directory\Model\Currency $currency, StockRegistry $stockRegistry,
     \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Forms\Registration\Model\DataExampleFactory $DataExampleFactory,
         \Magento\Framework\App\ResourceConnection $resourceConnection,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataExample $DataExample,
\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Framework\UrlInterface $url,
            \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
            \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,

        
        array $data = []) {
        $this->productFactory = $productFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->stockRegistry = $stockRegistry;
        $this->messageManager = $messageManager;
        $this->_currency = $currency;
        $this->_productloader = $_productloader;
        $this->DataExample = $DataExample;
        $this->DataExampleFactory = $DataExampleFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_storeManager = $storeManager;
        $this->_url = $url;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->_attributeFactory = $attributeFactory;
        $this->_productAttributeRepository = $productAttributeRepository;

        parent::__construct ( $context, $data );
    }
    
    /**
     * Set product collection uisng ProductFactory object
     *
     * @return void
     */
    protected function _construct() {
        parent::_construct ();
        $collection = $this->getFilterProducts ();
        $this->setCollection ( $collection );
    }
    
    public function getPagerHtml() {
        return $this->getChildHtml ( 'pager' );
    }
    public function getStockItem() {
        return $this->_scopeConfig->getValue('cataloginventory/item_options/notify_stock_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function customProductUrl($productId){
        return $customProductUrl = $this->_url->getUrl().'details/product/customise/id/'.$productId;
    }
    
    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }

    public function getVendorLogo($id){

        $this->_connection = $this->_resourceConnection->getConnection();
        $query = "SELECT * FROM data_example order by id DESC LIMIT 0,1"; 
        $collection = $this->_connection->fetchAll($query);
        return $collection;
    }

    public function getCoordinates($id){
        $this->_connection = $this->_resourceConnection->getConnection();
        $query = "SELECT * FROM catalog_product_coordinates_sides WHERE product_id = $id order by id DESC LIMIT 0,1"; 
        $collection = $this->_connection->fetchAll($query);
        return $collection;
    }

    public function getAllProductImages($product_id){
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      return $productimages = $product->getMediaGalleryImages();
    }

    public function getVendorLogoPath($logoName){
        $imagePath = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $imagePath .'VendorLogo/_'.$logoName;
    }

    /**
     * Get product approval or not
     *
     * @return int
     */
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        // $collection->setPageSize(1); // fetching only 3 products
        return $collection;
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

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted = false, $asCollection = false, $toLoad = true);
    }

    public function getAllBrand(){
        $manufacturerOptions = $this->_productAttributeRepository->get('brand')->getOptions();       
        $values = array();
        foreach ($manufacturerOptions as $manufacturerOption) { 
            $values[] = $manufacturerOption->getLabel();  // Label
        }
        return $values;
    }

    // public function getAllColors(){
    //     $manufacturerOptions = $this->_productAttributeRepository->get('color')->getOptions();       
    //     $values = array();
    //     foreach ($manufacturerOptions as $manufacturerOption) { 
    //         $values[] = $manufacturerOption->getLabel();  // Label
    //     }
    //     return $values;
    // }
}