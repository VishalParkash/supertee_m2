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


            // echo $quoteId = intval($this->_request->getParam('quoteId'));  die;
            // $postData = $this->request->getParams();
            $postData = $_REQUEST;
            echo "<pre>";print_r($postData);die;
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $cartsess = $objectManager->get('Magento\Checkout\Model\Session');
            $cartsess->setQuoteId($quoteId);
            $cartsess->setLoadInactive(true);
            $quote = $cartsess->getQuote();
            $quote->setIsActive(true);
            $quote->save();
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $this->_messangeManager->addSuccess('Product is successfully added in to cart.');   
            $redirectUrl = $this->_cartHelper->getCartUrl();
            $observer->getControllerAction()->getResponse()->setRedirect($redirectUrl);

            die;
        // $quoteId = intval($this->_request->getParam('quoteId'));
        // echo "<pre>";print_r($quoteId);die;

    $item = $observer->getEvent()->getQuoteItem();
    // $item = $observer->getEvent()->getData('quote_item');
    $item->getQuote()->collectTotals();
     // echo "<pre>";
     $postData = $this->request->getParams('newPrice');
     // print_r($newPrice['newPrice']);
     $newPrice = $postData['newPrice'];
     // $customSubtotal = 100; //You can set your custom subtotal amount
     // $customGrandTotal = 200; //You can set your custom Grand total amount
     $updatedSubtotal = $item->getQuote()->setSubtotal($newPrice); 
     $updatedGrandTotal = $item->getQuote()->setGrandTotal($newPrice);
     $item->setCustomPrice($newPrice);
     $item->setOriginalCustomPrice($newPrice);
     $item->getProduct()->setIsSuperMode(true);


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
}