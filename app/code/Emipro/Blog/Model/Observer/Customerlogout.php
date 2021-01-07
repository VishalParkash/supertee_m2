<?php

namespace Emipro\Blog\Model\Observer;
use Magento\Framework\Event\ObserverInterface;
class Customerlogout implements ObserverInterface
{
	protected $_messageManager;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->_messageManager = $messageManager;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $this->_messageManager->addSuccess(__("Thank You For Visit Our Website."));
    }
}