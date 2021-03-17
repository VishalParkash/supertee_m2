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

        // $product = $objectManager->get ( 'Magento\Catalog\Model\Product' )->load ( $assignProductId );
        // $productType = $product->getTypeId ();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $objectManager->create ( 'Magento\Catalog\Model\ResourceModel\Product\Collection' );

        if(!empty($this->getRequest()->getParam ( 'brand' )) && (!empty($this->getRequest()->getParam ( 'size' )))) {
    		$getBrand = $this->getRequest()->getParam ( 'brand' );
    		$getSize = $this->getRequest()->getParam ( 'size' );

    		$productCollection->addAttributeToSelect ( '*' )
        	->addAttributeToFilter ( 'size', $getSize )
        	->addAttributeToFilter ( 'brand', $getBrand );
         	
    	}elseif(!empty($this->getRequest()->getParam ( 'brand' ))) {
    		$getBrand = $this->getRequest()->getParam ( 'brand' );

    		$productCollection->addAttributeToSelect ( '*' )
        	->addAttributeToFilter ( 'brand', $getBrand );
         	
    	}elseif(!empty($this->getRequest()->getParam ( 'size' ))){
    		$getSize = $this->getRequest()->getParam ( 'size' );

    		$productCollection->addAttributeToSelect ( '*' )
        	->addAttributeToFilter ( 'size', $getSize );
    	}elseif(!empty($this->getRequest()->getParam ( 'priceRange' ))){
    		$priceRange = $this->getRequest()->getParam ( 'priceRange' );
    		$priceArr = explode("_", $priceRange);

    		$pricefrom = $priceArr[0];
    		$priceto = $priceArr[1];

    		$productCollection->addFieldToSelect ( '*' )
        	->addFieldToFilter ( 'price', array(
                                array('from' => $pricefrom, 'to' => $priceto),
                            ) );
    	}elseif((!empty($this->getRequest()->getParam ( 'categories' )))){
    		$categories = $this->getRequest()->getParam ( 'categories' );
            // echo "<pre>";print_r($categories);die;
            $categoriesStr = implode(",", $categories);
            $collection = $this->productCollectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addCategoriesFilter(['in' => $categoriesStr]);

    		// $category = $categoryFactory->create()->load($categoryId);
 
		// $productCollection = $category->getProductCollection()
    // ->addAttributeToSelect('*');
    	}
    	// echo $productCollection->getSelect();die;
    	// $productIds = array();
        if(!empty($collection)){
            $data = array();
            foreach($collection as $products){
                $data[] = $products->getId();
                
            }    
        }else{
            $data = array();
        }
    	

        	$block = $resultPage->getLayout()
                ->createBlock('Forms\Registration\Block\Index\View')
                ->setTemplate('Forms_Registration::view.phtml')
                ->setData('data',$data)
                ->toHtml();
 
        $result->setData(['output' => $block]);
        return $result;

    	// return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);

        /**
         * Apply filters here
         */
        



        // $post = (array) $this->getRequest()->getPost();
        // echo "<pre>";print_r($post);die;
    //     if (!empty($post)) {


    //         $getSize   = $post['Sizes'];
    //         $getBrand   = $post['brand'];

    //         $productcollection = $this->CollectionFactory->create()
    // ->addFieldToSelect('*')
    // ->addAttributeToFilter('size', $getBrand);
    // echo $productcollection->getSelect();die;
// echo "<pre>";print_r($productcollection->getSelect());

//             $data = ($this->_productloader->create()->loadByAttribute('size', $getBrand));
// echo "<pre>";print_r($productcollection);
            // foreach($data as $v){

            // }

            // $productIdArr = explode("_", $product);
            // $productId = $productIdArr[1];
            // $result = $this->resultJsonFactory->create();
            // $block = $this->_productloader->create()->load($productId);
            // $name = $block->getName();
            // $id = $block->getId();
            // $images = $block->getName();
            // foreach($block->getMediaGalleryImages() as $images){
            //     echo $images['url'];
            //     echo "<br>";
            // }

            // $result->setData(['output' => $block]);
            // return $result;

//             $products = $category->getProductCollection($id)->addAttributeToSelect('*')->addPriceData();
// // foreach ($products as $product) {
//     $response = array(
//         'id' => $product->getId(),
//         'media' => $product->getMediaGalleryImages(),
//         'name' => $product->getName(),
//         'image' => $product->getImage(),
//         'thumbnail' => $product->getThumbnail(),
//         'small_image' => $product->getSmallImage(),
//         'short_desc' => $product->getShortDescription(),
//         'price' => $product->getPrice(),
//         'special_price' => $product->getSpecialPrice(),
//         'final_price' => $product->getFinalPrice(),
//         // and so on for all you need...
//     );

//     return print_r($response);


        // }
        
    }
}