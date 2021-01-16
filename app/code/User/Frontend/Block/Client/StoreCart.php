<?php
namespace User\Frontend\Block\Client;

class StoreCart extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;;
        $this->cart = $cart;
        parent::__construct($context, $data);
    }

    /**
     * Get quote object associated with cart. By default it is current customer session quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuoteData()
    {
        $this->_checkoutSession->getQuote();
        if (!$this->hasData('quote')) {
            $this->setData('quote', $this->_checkoutSession->getQuote());
        }
        return $this->_getData('quote');
    }

    public function getCartData()
    {

        // return $this->cart->getItems();
        return $this->cart->getQuote()->getAllItems();


        // $this->_checkoutSession->getQuote();
        // if (!$this->hasData('quote')) {
        //     $this->setData('quote', $this->_checkoutSession->getQuote());
        // }
        // return $this->_getData('quote');
    }
}