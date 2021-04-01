<?php
namespace Forms\Registration\Block\Zeke;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\AttributeSetRepositoryInterface;
use \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
class Zeke extends \Magento\Framework\View\Element\Template
{
	protected $_productCollectionFactory;
    protected $imageHelper;
    protected $productFactory;
    // protected $attributeSetCollection;
    protected $_attributeSetCollection;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Image $imageHelper,
        ProductFactory $productFactory,
        AttributeSetRepositoryInterface $attributeSetRepository,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        $this->_attributeSetCollection = $attributeSetCollection;

        // $this->attributeSetRepository = $attributeSetRepository;
        parent::__construct($context, $data);
    }
    
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(3); // fetching only 3 products
        return $collection;
    }

    public function getAttributeSetId($attributeSetName)
      {
        $attributeSetCollection = $this->_attributeSetCollection->create()
          ->addFieldToSelect('attribute_set_id')
          ->addFieldToFilter('attribute_set_name', $attributeSetName)
          ->getFirstItem()
          ->toArray();

        $attributeSetId = (int) $attributeSetCollection['attribute_set_id'];
        // OR (see benchmark below for make your choice)
        $attributeSetId = (int) implode($attributeSetCollection);

        return $attributeSetId;
      }

    public function getProductImageUrl($id)
    {
    try {
    $product = $this->productFactory->create()->load($id);
    } catch (NoSuchEntityException $e) {
    return 'Data not found';
    }
    $url = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    return $url;
    }
}