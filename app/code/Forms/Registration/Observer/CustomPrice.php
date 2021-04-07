<?php

namespace Forms\Registration\Observer;

use \Magento\Framework\Event\ObserverInterface;

/**
 * Class CustomPrice
 * @package Aureatelabs\CustomPrice\Observer
 *
 */
class CustomPrice implements ObserverInterface
{

    protected $request;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,

        // \Magento\Framework\App\Request\Http $request
        \Magento\Framework\App\RequestInterface $request

    ) {
        $this->request = $request;
        
    }


    public function execute(\Magento\Framework\Event\Observer $observer) {


        // $item = $observer->getEvent()->getData('quote_item');

        // $product = $observer->getEvent()->getData('product');
        // echo "<pre>";print_r($item);;
        // echo "<pre>";print_r($product);die;

        // $price ='';

        // $cartItems = [];
        // if($item->getQuote()->getItems()){
        //     foreach ($item->getQuote()->getItems() as $key => $value) {
        //         $cartItems[$value->getSku()] = $value->getQty();
        //     }
        // }

        // // add your logic for custom price
        // $item->setOriginalCustomPrice($price);
        // $item->setCustomPrice($price);
        // die;
        //     // echo $quoteId = intval($this->_request->getParam('quoteId'));  die;
        //     // $postData = $this->request->getParams();
        //     // $postData = $_REQUEST;
        //     // echo "<pre>";print_r($postData);die;
        //     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //     $cartsess = $objectManager->get('Magento\Checkout\Model\Session');
        //     $cartsess->setQuoteId($quoteId);
        //     $cartsess->setLoadInactive(true);
        //     $quote = $cartsess->getQuote();
        //     $quote->setIsActive(true);
        //     $quote->save();
        //     $quote->setTotalsCollectedFlag(false)->collectTotals();
        //     $this->_messangeManager->addSuccess('Product is successfully added in to cart.');   
        //     $redirectUrl = $this->_cartHelper->getCartUrl();
        //     $observer->getControllerAction()->getResponse()->setRedirect($redirectUrl);

        //     die;
        // $quoteId = intval($this->_request->getParam('quoteId'));
        // echo "<pre>";print_r($quoteId);die;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $item = $observer->getEvent()->getQuoteItem();
    $parentProductIds = array();
        // echo "<pre>";print_r($item);die;
    // $item = $observer->getEvent()->getData('quote_item');
    $item->getQuote()->collectTotals();
     // echo "<pre>";
     $postData = $this->request->getParams();
     // echo "<pre>";print_r($postData);die;
     $productQuantity = $postData['productQuantity'];
     $getChildProductsId = $postData['getChildProductsId'];

     foreach($productQuantity as $products => $quantity){

        $explodeProduct = explode("_", $products);
        $productId = $explodeProduct[1];
        // echo "print ".$productId;
        // echo "<br>";

        $product = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
        if(isset($product[0])){
             $parentProductIds[$product[0]][] = $quantity;
             $getParentId = $product[0];
        }
     }
     // echo "<pre>";print_r($parentProductIds);
        $QtyArr = array();
        foreach($parentProductIds as $key => $var){
            $sum = 0;
            foreach($var as $value){
                if(!empty($value)){$sum += $value;}
                
            }
            $QtyArr[$key] = $sum;
        }
        // echo "<pre>";print_r($QtyArr);

        foreach($productQuantity as $product => $quantity){

            $explodeProduct = explode("_", $products);
            $productId = $explodeProduct[1];
            // $productId = $_item->getProductId();
            $product = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($productId);
            $productIns = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            if(isset($product[0])){
                $itemQty = $this->getQty($product[0], $QtyArr);
                $customPrice = ($productIns->getTierPrice($itemQty));
                

                // $product_obj = $objectManager->create('Magento\Catalog\Model\Product')->load($product[0]);                
                // $tier_price = $product_obj->getTierPrice();
                // if(count($tier_price) > 0){
                //     foreach($tier_price as $pirces){
                //         foreach ( array_reverse($pirces) as $k => $v ) {
                //             if($k == "price"){
                //                 $tp = number_format($v, 2, '.', '');
                //                 echo $tp;
                //             }
                //         }
                //     }
                // }


                $item->setCustomPrice($customPrice);
                $item->setOriginalCustomPrice($customPrice);
            }
                
        }
        // die;
     









     // $customSubtotal = 100; //You can set your custom subtotal amount
     // $customGrandTotal = 200; //You can set your custom Grand total amount
     // $updatedSubtotal = $item->getQuote()->setSubtotal($customSubtotal); 
     // $updatedGrandTotal = $item->getQuote()->setGrandTotal($customGrandTotal);
     // $item->setCustomPrice(20);
     // $item->setOriginalCustomPrice(20);
     // $item->getProduct()->setIsSuperMode(true);
     // $item->getQuote()->collectTotals();


        // $item = $observer->getEvent()->getData('quote_item');

        // // Get parent product if current product is child product
        // $item = ( $item->getParentItem() ? $item->getParentItem() : $item );

        // // echo "<pre>";print_r($item->getData());
        // // $post = (array) $this->getRequest()->getPost();
        // // $post = $this->request->getPost();
        // // $reqeustParams = $this->request->getParams('updatedPrice');

        
        
        // // // die;

        // // //Define your Custom price here
        // // $price = ($post['updatedPrice']);
     
        // // //Set custom price
        // // $item->setCustomPrice($price);
        // // $item->setOriginalCustomPrice($price);
        // // $item->getProduct()->setIsSuperMode(true);
    }

    function getQty($parentProductId, $arr){
    foreach($arr as $qty){
        return $qty;
    }
}
}