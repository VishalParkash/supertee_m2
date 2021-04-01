<?php
namespace Forms\Registration\Block\Account;

class ResetPassword extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get quote object associated with cart. By default it is current customer session quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    // public function getUserData()
    // {
    
    //     return 'Helloooooo';
    // }
}