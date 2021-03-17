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
        $item = $observer->getEvent()->getData('quote_item');

        // Get parent product if current product is child product
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );

        // echo "<pre>";print_r($item->getData());
        // $post = (array) $this->getRequest()->getPost();
        $post = $this->request->getPost();
        $reqeustParams = $this->request->getParams('updatedPrice');

        
        
        // die;

        //Define your Custom price here
        $price = ($post['updatedPrice']);
     
        //Set custom price
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->getProduct()->setIsSuperMode(true);
    }
}