<?php
namespace Forms\Registration\Block\Checkout;

class Cart extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;;
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
}