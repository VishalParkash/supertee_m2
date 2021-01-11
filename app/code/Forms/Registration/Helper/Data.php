<?php
namespace Forms\Registration\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function isLoggedIn()
    {

         if($this->customerSession->isLoggedIn()){
            return $this->customerSession->isLoggedIn();
        }else{
            return false;
        }
    }
}