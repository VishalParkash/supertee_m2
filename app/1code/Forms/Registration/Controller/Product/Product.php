<?php
namespace Forms\Registration\Controller\Product;
use Magento\Framework\Controller\Result\JsonFactory;

class Product extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        JsonFactory $resultJsonFactory,

        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->_productloader = $_productloader;
        $this->resultJsonFactory = $resultJsonFactory;

        return parent::__construct($context);
    }

    public function execute()
    {
        $post = (array) $this->getRequest()->getPost();
        echo "<pre>";print_r($post);die;
        if (!empty($post)) {
            $product   = $post['id'];
            $productIdArr = explode("_", $product);
            $productId = $productIdArr[1];
            $result = $this->resultJsonFactory->create();
            $block = $this->_productloader->create()->load($productId);
            $name = $block->getName();
            $id = $block->getId();
            $images = $block->getName();
            foreach($block->getMediaGalleryImages() as $images){
                echo $images['url'];
                echo "<br>";
            }

            $result->setData(['output' => $block]);
            return $result;

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


        }
        
    }
}