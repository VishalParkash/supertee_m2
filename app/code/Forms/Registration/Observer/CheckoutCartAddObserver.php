<?php 
namespace Forms\Registraion\Observer;


use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class CheckoutCartAddObserver implements ObserverInterface
{
public function execute(\Magento\Framework\Event\Observer $observer)
  {

     $item = $observer->getEvent()->getQuoteItem();
     $item->getQuote()->collectTotals();
     $customSubtotal = 100; //You can set your custom subtotal amount
     $customGrandTotal = 200; //You can set your custom Grand total amount
     $updatedSubtotal = $item->getQuote()->setSubtotal($customSubtotal); 
     $updatedGrandTotal = $item->getQuote()->setGrandTotal($customGrandTotal);               
   }
}