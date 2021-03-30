<?php
namespace Forms\Registration\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart as CustomerCart;

class Remove extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @param Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param CustomerCart $cart
     */
    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        CustomerCart $cart
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;

        parent::__construct($context);
    }

    public function execute()
    {

        $items = $this->_checkoutSession->getQuote()->getAllItems();
$createdAt = $this->_stdTimezone->date()->format('Y-m-d H:i:s');
foreach ($items as $key => $item) {
    if (strtotime($customModel->getExpireTime()) <= strtotime($createdAt)) {
        $this->_itemModel->load($item->getItemId())->delete();
    }
}
$this->_cart->save();

        $allItems = $this->checkoutSession->getQuote()->getAllVisibleItems();
        foreach ($allItems as $item) {
            $itemId = $item->getItemId();
            $this->cart->removeItem($itemId)->save();
        }

        $message = __(
            'You deleted all item from shopping cart.'
        );
        $this->messageManager->addSuccessMessage($message);

        $response = [
            'success' => true,
        ];

        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($response)
        );
    }
}