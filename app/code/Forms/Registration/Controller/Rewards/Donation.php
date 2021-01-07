<?php
namespace Forms\Registration\Controller\Rewards;
use Magento\Framework\Controller\Result\JsonFactory;

class Donation extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultJsonFactory;
    protected $CollectionFactory;
    protected $productCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Forms\Registration\Model\Session $session,

        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,

        // \Magento\Eav\Model\Entity\Collection\AbstractCollection $productCollectionFactory=null,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $CollectionFactory,
        // \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection,
        // \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection ,

        JsonFactory $resultJsonFactory,

        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->session = $session;
        $this->_productloader = $_productloader;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->CollectionFactory = $CollectionFactory;

        return parent::__construct($context);
    }

    public function execute(){

    	$result = $this->resultJsonFactory->create();
        $resultPage = $this->_pageFactory->create();

        try {
                $post = (array) $this->getRequest()->getPost();
                $this->session->setData("donation_data", $post);
                $result->setData(['output' => $post]);
                return $result;
                
                } catch (Exception $e) {
                    \Zend_Debug::dump($e->getMessage());
                }


        
  //       if(!empty($this->getRequest()->getParam ( 'brand' )) && (!empty($this->getRequest()->getParam ( 'size' )))) {
  //   		$getBrand = $this->getRequest()->getParam ( 'brand' );
  //   		$getSize = $this->getRequest()->getParam ( 'size' );

  //   		$productCollection->addAttributeToSelect ( '*' )
  //       	->addAttributeToFilter ( 'size', $getSize )
  //       	->addAttributeToFilter ( 'brand', $getBrand );
         	
  //   	}elseif(!empty($this->getRequest()->getParam ( 'brand' ))) {
  //   		$getBrand = $this->getRequest()->getParam ( 'brand' );

  //   		$productCollection->addAttributeToSelect ( '*' )
  //       	->addAttributeToFilter ( 'brand', $getBrand );
         	
  //   	}elseif(!empty($this->getRequest()->getParam ( 'size' ))){
  //   		$getSize = $this->getRequest()->getParam ( 'size' );

  //   		$productCollection->addAttributeToSelect ( '*' )
  //       	->addAttributeToFilter ( 'size', $getSize );
  //   	}elseif(!empty($this->getRequest()->getParam ( 'priceRange' ))){
  //   		$priceRange = $this->getRequest()->getParam ( 'priceRange' );
  //   		$priceArr = explode("_", $priceRange);

  //   		$pricefrom = $priceArr[0];
  //   		$priceto = $priceArr[1];

  //   		$productCollection->addFieldToSelect ( '*' )
  //       	->addFieldToFilter ( 'price', array(
  //                               array('from' => $pricefrom, 'to' => $priceto),
  //                           ) );
  //   	}elseif((!empty($this->getRequest()->getParam ( 'categories' )))){
  //   		$categoryId = $this->getRequest()->getParam ( 'categories' );
  //   		$category = $categoryFactory->create()->load($categoryId);
 
		// $productCollection = $category->getProductCollection()
  //   ->addAttributeToSelect('*');
  //   	}
  //   	// echo $productCollection->getSelect();die;
  //   	$productIds = array();
  //   	foreach($productCollection as $products){
	 //         	$data[] = $products->getId();
	         	
  //       	}

  //       	$block = $resultPage->getLayout()
  //               ->createBlock('Forms\Registration\Block\Index\View')
  //               ->setTemplate('Forms_Registration::view.phtml')
  //               ->setData('data',$data)
  //               ->toHtml();
 
  //       $result->setData(['output' => $block]);
  //       return $result;
        
    }
}