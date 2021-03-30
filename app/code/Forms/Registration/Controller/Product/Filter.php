<?php
namespace Forms\Registration\Controller\Product;
use Magento\Framework\Controller\Result\JsonFactory;

class Filter extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultJsonFactory;
    protected $CollectionFactory;
    protected $productCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,

        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,

        // \Magento\Eav\Model\Entity\Collection\AbstractCollection $productCollectionFactory=null,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $CollectionFactory,
        // \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection,
        // \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection ,

        JsonFactory $resultJsonFactory,

        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->_productloader = $_productloader;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->CollectionFactory = $CollectionFactory;

        return parent::__construct($context);
    }

    public function execute(){

    	$result = $this->resultJsonFactory->create();
        $resultPage = $this->_pageFactory->create();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
        $orderBy = $this->getRequest()->getParam ('orderType');
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $objectManager->create ( 'Magento\Catalog\Model\ResourceModel\Product\Collection' );
        $childproduct = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable');
        
        
        
        // if(!empty($this->getRequest()->getParam ( 'brand' )) && (!empty($this->getRequest()->getParam ( 'size' )))) {
    	// 	$getBrand = $this->getRequest()->getParam ( 'brand' );
    	// 	$getSize = $this->getRequest()->getParam ( 'size' );

    	// 	$productCollection->addAttributeToSelect ( '*' )
        // 	->addAttributeToFilter ( 'size', $getSize )
        // 	->addAttributeToFilter ( 'brand', $getBrand );
         	
    	// }else
        if(!empty($this->getRequest()->getParam ( 'brand' ))) {
            // Category Filter
            if((!empty($this->getRequest()->getParam ( 'categories' )))) {
                $productCollection = $this->categoryFilter();
            }

            // Price Filter
            if(!empty($this->getRequest()->getParam ( 'priceRange' ))) {
                $productCollection = $this->priceFilter($productCollection);
            }
    		
            if(!empty($this->getRequest()->getParam ( 'size' ))){
                $productCollection = $this->sizeFilter($productCollection);
            }
         	// Brand Filter
             $productCollection = $this->brandFilter($productCollection);

    	}elseif(!empty($this->getRequest()->getParam ( 'size' ))){
            // Category Filter
            if((!empty($this->getRequest()->getParam ( 'categories' )))) {
                $productCollection = $this->categoryFilter();
            }
            // Brand Filter
            if(!empty($this->getRequest()->getParam ( 'brand' ))) {
                $productCollection = $this->brandFilter($productCollection);
            }
            // Price Filter
            if(!empty($this->getRequest()->getParam ( 'priceRange' ))) {
                $productCollection = $this->priceFilter($productCollection);
            }

    		$productCollection = $this->sizeFilter($productCollection);

    	}elseif(!empty($this->getRequest()->getParam ( 'priceRange' ))){

    		

            // Category Filter
            if((!empty($this->getRequest()->getParam ( 'categories' )))) {
                $productCollection = $this->categoryFilter();
            }
            if(!empty($this->getRequest()->getParam ( 'size' ))){
                $productCollection = $this->sizeFilter($productCollection);
            }
            // price range
            $productCollection = $this->priceFilter($productCollection);

            
    	}elseif((!empty($this->getRequest()->getParam ( 'categories' )))){

    		// Category Filter
            if((!empty($this->getRequest()->getParam ( 'categories' )))) {
                $productCollection = $this->categoryFilter();
            }

    	}

        // sorting data
        $sorting = '';
        if (!empty($orderBy)) {
              $sorting = $orderBy;
        }

        if(!empty($productCollection)){
            $data = array();
            foreach($productCollection as $products){
                //echo '<pre>';print_r($products->getData());die();
                $childproductcheck = $childproduct->getParentIdsByChild($products->getId());
                if (isset($childproductcheck[0])) {
                    $data[] = $childproductcheck[0];
                } else {
                    $data[] = $products->getId();
                }
            }
        }else{
            $data = array();
        }

        $block = $resultPage->getLayout()
            ->createBlock('Forms\Registration\Block\Index\View')
            ->setTemplate('Forms_Registration::view.phtml')
            ->setData('data',['data' =>array_unique($data),'sort' => $sorting])
            ->toHtml();
 
        $result->setData(['output' => $block]);
        return $result;
        
    }

    public function categoryFilter () {
        $categories = $this->getRequest()->getParam ( 'categories' );
        $categoriesStr = implode(",", $categories);
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $productCollection->addCategoriesFilter(['in' => $categoriesStr]);
        return $productCollection;
    }

    public function priceFilter($productCollection) {
        $priceRange = $this->getRequest()->getParam ( 'priceRange' );
            $filterPrice = array();
            foreach($priceRange as $key){
                $priceArr = explode("_", $key);
                foreach($priceArr as $val) {
                    array_push($filterPrice,$val);
                }
            }
            sort($filterPrice);
    		$pricefrom = $filterPrice[0];
    		$priceto = $filterPrice[count($filterPrice)-1];
            $productCollection->addAttributeToFilter('price', ['gt' => $pricefrom]);
            if ($priceto != 'gt'){$productCollection->addAttributeToFilter('price', ['lt' => $priceto]);}
            return $productCollection;
    }

    public function brandFilter($productCollection){
        $getBrand = $this->getRequest()->getParam ( 'brand' );
        $getBrandStr = implode(",", $getBrand);
    	$productCollection->addAttributeToSelect ( '*' )->addAttributeToFilter ( [
            ['attribute' => 'brand', 'in' => $getBrandStr]
           ]);
        return $productCollection;
    }

    public function sizeFilter($productCollection) {
        $getSize = $this->getRequest()->getParam ( 'size' );
        $getSizeStr = implode(",", $getSize);
    	$productCollection->addAttributeToSelect ( '*' )->addAttributeToFilter ( [
            ['attribute' => 'size', 'in' => $getSizeStr]
           ]);
        return $productCollection;
    }

}