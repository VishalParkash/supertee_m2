<?php
/**
 * Copyright © 2015 Create . All rights reserved.
 */
namespace Create\Store\Block\Store;
use Create\Store\Block\BaseBlock;
class Create extends \Magento\Framework\View\Element\Template
{
	public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,

    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
    array $data = []
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct( $context, $data);
    }
	

	public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');        
        // $collection->addAttributeToSelect('image');        
        
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
