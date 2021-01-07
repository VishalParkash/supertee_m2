<?php
/**
 * @author DCKAP Team
 * @copyright Copyright (c) 2017 DCKAP (https://www.DCKAP.com)
 * @package DCKAP_Showcategories
 *
 * Copyright Â© 2017 DCKAP. All rights reserved.
 *
 */
?>
<?php
namespace Forms\Registration\Block\Widget;
 
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
 
class Featuredproducts extends \Magento\Framework\View\Element\Template implements BlockInterface
{
 
    protected $_template = 'widget/featuredproducts.phtml';
    protected $categoryRepository;
    protected $_categoryCollectionFactory;
    protected $_storeManager;

    protected $_productCollectionFactory;
    protected $_productloader;
 
    public function __construct(Context $context, StoreManagerInterface $storeManager, CollectionFactory $categoryCollectionFactory, \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory)
    {
 
        $this->_storeManager = $storeManager;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;

        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productloader = $_productloader;
        parent::__construct($context);
    }
 
    /**
     * Get value of widgets' title parameter
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }
 
    /**
     * Retrieve Category ids
     *
     * @return string
     */
    public function getCategoryIds()
    {
        if ($this->hasData('categoryids')) {
            return $this->getData('categoryids');
        }
        return $this->getData('categoryids');
    }
 
    /**
     *  Get the category collection based on the ids
     *
     * @return array
     */
    public function getCategoryCollection()
    {
        $category_ids = explode(",", $this->getCategoryIds());
        $condition = ['in' => array_values($category_ids)];
 
        $collection = $this->_categoryCollectionFactory->create()->addAttributeToFilter('entity_id', $condition)->addAttributeToSelect(['name', 'is_active', 'parent_id', 'image'])->setStoreId($this->_storeManager->getStore()->getId());
        return $collection;
    }

    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(6); // fetching only 6 products
        return $collection;
    }

    public function getLoadProduct($id)
    {
        return $this->_productloader->create()->load($id);
    }
}