<?php
namespace Forms\Registration\Block\Product;

use Magento\Catalog\Helper\Output as OutputHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Layer\Resolver;
// use Magento\Framework\View\Element\Template\Context;
class ListProduct extends \Magento\Framework\View\Element\Template
{
	// public function __construct(
		
	// 	\Magento\Framework\Data\Helper\PostHelper $postDataHelper,
	// 	\Forms\Registration\Model\Layer\Resolver $layerResolver,
	// 	\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
	// 	\Magento\Framework\Url\Helper\Data $urlHelper,
	// 	array $data = []
	// ) {
	// 	parent::__construct($context, $postDataHelper, $layerResolver,
	// 		$categoryRepository, $urlHelper, $data);
	// }

protected $_objectManager = null;
protected $_categoryFactory;
protected $_category;
protected $_productCollectionFactory;
protected $_registry;
protected $_catalogData = null;
protected $_catalogLayer;
protected $_productAttributeRepository;
protected $_categoryCollectionFactory;

public function __construct(
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
    // \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Catalog\Block\Product\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Framework\Registry $registry,
    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
    Data $catalogData,
    Resolver $layerResolver,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
    \Magento\Catalog\Model\Product\Attribute\Repository $productAttributeRepository,
    array $data = [],
    ?OutputHelper $outputHelper = null
) {
    $this->_productCollectionFactory = $productCollectionFactory;
    $this->_objectManager = $objectManager;
    $this->_registry = $registry;
    $this->_catalogLayer = $layerResolver->get();
    $this->_categoryFactory = $categoryFactory;
    $this->_catalogData = $catalogData;
    $this->_productAttributeRepository = $productAttributeRepository;
    $this->_categoryCollectionFactory = $categoryCollectionFactory;

    $data['outputHelper'] = $outputHelper ?? ObjectManager::getInstance()->get(OutputHelper::class);

    parent::__construct($context, $data);
}
	
	protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }

        return $this->_productCollection;
    }

    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        /* @var $layer Layer */
        if ($this->getShowRootCategory()) {
            $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        }

        // if this is a product view page
        if ($this->_coreRegistry->registry('product')) {
            // get collection of categories this product is associated with
            $categories = $this->_coreRegistry->registry('product')
                ->getCategoryCollection()->setPage(1, 1)
                ->load();
            // if the product is associated with any category
            if ($categories->count()) {
                // show products from this category
                $this->setCategoryId(current($categories->getIterator())->getId());
            }
        }

        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }
        $collection = $layer->getProductCollection();

        $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );

        return $collection;
    }

    public function getLayer()
    {
        return $this->_catalogLayer;
    }

public function getCurrentCategory()
{
    // $category = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_category');
    $category = $this->_registry->registry('current_category');//get current category


    return $category;
}

/**
 * Get category object
 *
 * @return \Magento\Catalog\Model\Category
 */
public function getCategory($categoryId)
{
    $this->_category = $this->_categoryFactory->create();
    $this->_category->load($categoryId);
    return $this->_category;
}

/**
 * Get all children categories IDs
 *
 * @param boolean $asArray return result as array instead of comma-separated list of IDs
 * @return array|string
 */
public function getAllChildren($asArray = false, $categoryId = false)
{
    if ($this->_category) {
        return $this->_category->getAllChildren($asArray);
    } else {
        return $this->getCategory($categoryId)->getAllChildren($asArray);
    }
}

public function getProductCollection($category_id_array)
{
    $collection = $this->_productCollectionFactory->create();
    $collection->addAttributeToSelect('*');
    $collection->addCategoriesFilter(['in' => $category_id_array]);
    $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
    $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
    $collection->setPageSize(9); // fetching only 9 products
    return $collection;
}

public function getProductPrice(Product $product)
    {
        $priceRender = $this->getPriceRender();

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                FinalPrice::PRICE_CODE,
                $product,
                [
                    'include_container' => true,
                    'display_minimal_price' => true,
                    'zone' => Render::ZONE_ITEM_LIST,
                    'list_category_page' => true
                ]
            );
        }

        return $price;
    }

    /**
     * Specifies that price rendering should be done for the list of products.
     * (rendering happens in the scope of product list, but not single product)
     *
     * @return Render
     */
    protected function getPriceRender()
    {
        return $this->getLayout()->getBlock('product.price.render.default')
            ->setData('is_product_list', true);
    }

    public function getAllProductImages($product_id){
      $objectmanager = \Magento\Framework\App\ObjectManager::getInstance();
      $productimages = array();
      $product = $objectmanager ->create('Magento\Catalog\Model\Product')->load($product_id);
      return $productimages = $product->getMediaGalleryImages();
    }


    

    /**
     * Retrieve HTML title value separator (with space)
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getTitleSeparator($store = null)
    {
        $separator = (string)$this->_scopeConfig->getValue(
            'catalog/seo/title_separator',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return ' ' . $separator . ' ';
    }

    /**
     * Preparing layout
     *
     * @return \Magento\Catalog\Block\Breadcrumbs
     */
    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );

            $title = [];
            $path = $this->_catalogData->getBreadcrumbPath();

            foreach ($path as $name => $breadcrumb) {
                $breadcrumbsBlock->addCrumb($name, $breadcrumb);
                $title[] = $breadcrumb['label'];
            }

            $this->pageConfig->getTitle()->set(join($this->getTitleSeparator(), array_reverse($title)));
        }
        return parent::_prepareLayout();
    }

    public function getAllBrand(){
        return $manufacturerOptions = $this->_productAttributeRepository->get('brand')->getOptions();       
        $values = array();
        foreach ($manufacturerOptions as $manufacturerOption) { 
            $values[] = $manufacturerOption->getLabel();  // Label
        }
        return $values;
    }

    public function getAllSizes(){
        return $manufacturerOptions = $this->_productAttributeRepository->get('size')->getOptions();       
        $values = array();
        foreach ($manufacturerOptions as $manufacturerOption) { 
            $values[] = $manufacturerOption->getLabel();  // Label
        }
        return $values;
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

}