<?php
namespace User\Frontend\Block\Widget;

class ListCategories extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'widget/categorieslist.phtml';

    const DEFAULT_IMAGE_WIDTH = 250;
    const DEFAULT_IMAGE_HEIGHT = 250;
    
    /**
    * \Magento\Catalog\Model\CategoryFactory $categoryFactory
    */
    protected $_categoryFactory;
    
    /**
    * @param \Magento\Framework\View\Element\Template\Context $context
    * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
    * @param array $data
    */
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Catalog\Model\CategoryFactory $categoryFactory,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context);
    }

    /**
    * Retrieve current store categories
    *
    * @return \Magento\Framework\Data\Tree\Node\Collection|\Magento\Catalog\Model\Resource\Category\Collection|array
    */
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

    protected function getImageUrl($image)
 {
     $url = $image;
     if ($image) {
         if (is_string($image)) {
             $url = $this->storeManager->getStore()->getBaseUrl(
                     \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                 ) . 'catalog/category/' . $image;
         } else {
             throw new \Magento\Framework\Exception\LocalizedException(
                 __('Something went wrong while getting the image url.')
             );
         }
     }
 
     return $url;
 }
    
    /**
    * Get the width of product image
    * @return int
    */
    public function getImageWidth() {
        if($this->getData('imagewidth')==''){
            return DEFAULT_IMAGE_WIDTH;
        }
        return (int) $this->getData('imagewidth');
    }

    /**
    * Get the height of product image
    * @return int
    */
    public function getImageHeight() {
        if($this->getData('imageheight')==''){
            return DEFAULT_IMAGE_HEIGHT;
        }
        return (int) $this->getData('imageheight');
    }
    
    public function canShowImage(){
        if($this->getData('image') == 'image')
            return true;
        elseif($this->getData('image') == 'no-image')
            return false;
    }
}