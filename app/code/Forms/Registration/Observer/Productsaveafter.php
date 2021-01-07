<?php

namespace Forms\Registration\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_product = $observer->getProduct();  // you will get product object
        echo "<pre>";print_r($_product);die;
        $_sku=$_product->getSku(); // for sku

    }   
}