<?php

namespace Forms\Registration\Block\Category;

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
class Category extends \Magento\Framework\View\Element\Template {
    
    protected $_objectManager = null;
protected $_categoryFactory;
protected $_category;
protected $_productCollectionFactory;

public function __construct(
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Catalog\Model\CategoryFactory $categoryFactory
) {
    $this->_productCollectionFactory = $productCollectionFactory;
    $this->_objectManager = $objectManager;
    $this->_categoryFactory = $categoryFactory;
    parent::__construct($context);
}


public function getCurrentCategory()
{
    $category = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_category');
    return $category;
}

/**
 * Get category object
 *
 * @return \Magento\Catalog\Model\Category
 */
public function getCategory($categoryId = 25)
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
}