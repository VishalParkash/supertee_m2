<?php 
namespace Forms\Registration\Controller\Cart;

use Magento\Framework\Controller\ResultFactory;
class Coupon extends \Magento\Framework\App\Action\Action{


protected $_cart;
protected $productRepository;
protected $_redirect;
protected $_url;
protected $productAttributeRepository;

public function __construct(\Magento\Framework\App\Action\Context $context,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\Framework\UrlInterface $url,
    \Magento\Framework\App\Response\Http $redirect,
    \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository

)
{   parent::__construct($context);
    $this->_cart = $cart;
    $this->productRepository = $productRepository;
    $this->_url = $url;
    $this->_redirect = $redirect;
    $this->productAttributeRepository = $productAttributeRepository;

}

    public function execute() {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');

        $post = (array) $this->getRequest()->getPost();
        // echo "<pre>";print_r($post);die;
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);


        $cart = $this->_cart;
        $couponCode = $post['coupon_code'];
        // echo "<pre>";print_r($cart->getQuote());die;

//         foreach($cart->getQuote()->getAllVisibleItems() as $_item) {
//     echo "<pre>";print_r($_item->debug());
//     echo 'ID: '.$_item->getProductId().'<br/>';
//     echo 'Name: '.$_item->getName().'<br/>';
//     echo 'Sku: '.$_item->getSku().'<br/>';
//     echo 'Quantity: '.$_item->getQty().'<br/>';
//     echo 'Price: '.$_item->getProduct()->getPrice().'<br/>';
//     echo 'Product Type: '.$_item->getProductType().'<br/>';
//     echo 'Discount: '.$_item->getDiscountAmount();echo "<br/>";
//     echo 'Discount: '.$_item->getProduct()->getAttributeText('size');
//     echo "tier " .$_item->getProduct()->getTierPrice($_item->getQty());
// echo "<br/>";
// echo "quoteid" .$_item->getId();
//     echo "<br/>";

//     $grandTotal +=   ($_item->getProduct()->getTierPrice($_item->getQty()))*($_item->getQty());

//     if(!empty($_item->getDiscountAmount())){
//         $grandTotal = (($grandTotal) - ($_item->getDiscountAmount()));
//         $disableElement = "disabled";
//     }else{
//         $disableElement = "";
//     }
//     $_item->getQuote()->setGrandTotal($grandTotal);

// }

    if(!empty($couponCode))
    {
        // $grandTotal = 0;
        // $discountTotal = 0;
        $quote = $cart->getQuote()->setCouponCode($couponCode)->collectTotals()->save();
//               foreach($cart->getQuote()->getAllVisibleItems() as $_item) {
//     echo "<pre>";print_r($_item->debug());
//     echo 'ID: '.$_item->getProductId().'<br/>';
//     echo 'Name: '.$_item->getName().'<br/>';
//     echo 'Sku: '.$_item->getSku().'<br/>';
//     echo 'Quantity: '.$_item->getQty().'<br/>';
//     echo 'Price: '.$_item->getProduct()->getPrice().'<br/>';
//     echo 'Product Type: '.$_item->getProductType().'<br/>';
//     echo 'Discount: '.$_item->getDiscountAmount();echo "<br/>";
//     echo 'Discount: '.$_item->getProduct()->getAttributeText('size');
//     echo "tier " .$_item->getProduct()->getTierPrice($_item->getQty());
// echo "<br/>";
// echo "quoteid" .$_item->getId();
//     echo "<br/>";

//     $grandTotal +=   ($_item->getProduct()->getTierPrice($_item->getQty()))*($_item->getQty());
//     $discountTotal += $_item->getDiscountAmount();

//     if(!empty($_item->getDiscountAmount())){
//         $grandTotal = (($grandTotal) - ($_item->getDiscountAmount()));
//         $disableElement = "disabled";
//     }else{
//         $disableElement = "";
//     }
//     $_item->getQuote()->setGrandTotal($grandTotal);

// }
// echo "discountTotal..".$discountTotal;
// echo $cart->getQuote()->getSubtotal();die;

    } else {
        $quote = $cart->getQuote()->setCouponCode('')->collectTotals()->save();
    }


        header("Location:".$request->getServer('HTTP_REFERER'));
        die;

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;


        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
        

        
    }
}