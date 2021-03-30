<?php
/*
 * Turiknox_CustomTotal

 * @category   Turiknox
 * @package    Turiknox_CustomTotal
 * @copyright  Copyright (c) 2017 Turiknox
 * @license    https://github.com/turiknox/magento2-custom-total/blob/master/LICENSE.md
 * @version    1.0.0
 */
namespace Turiknox\CustomTotal\Model\Total;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;

use Turiknox\CustomTotal\Helper\Data as CustomTotalHelper;

class Custom extends AbstractTotal
{

    /**
     * @var CustomTotalHelper
     */
    protected $helper;

    /**
     * Custom constructor.
     *
     * @param CustomTotalHelper $helper
     */
    public function __construct(
        CustomTotalHelper $helper,
        \Forms\Registration\Model\Session $session,
        \Magento\Customer\Model\Session $customerSession

    ) {
        $this->helper = $helper;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->setCode('custom');
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        if($this->customerSession->isLoggedIn()) { 
            if($this->session->getData("discountVar") == "minus" ){
            $amount = $this->helper->getAmount($this->customerSession->getCustomer()->getId());

            // if($total->getGrandTotal() > $amount){
                $amount = (-$amount);
                $total->setGrandTotal((($total->getGrandTotal()) + ($amount)));
                $total->setBaseGrandTotal((($total->getBaseGrandTotal()) + ($amount)));
            // }
            
            // $total->addTotalAmount('custom', $amount);
            // $total->setTotalAmount('custom', $amount);
            // $total->setBaseTotalAmount('custom', $amount);
            // $total->setCustom($amount);
            // $total->setBaseCustom($amount);

            
            }
        }

        return $this;
    }

    /**
     * @param Total $total
     */
    protected function clearValues(Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        if($this->customerSession->isLoggedIn()) { 
            if($this->session->getData("discountVar") == "minus" ){
            $amount = $this->helper->getAmount($this->customerSession->getCustomer()->getId());
        }else{
            $amount = 0;
        }
    }else{
        $amount = 0;
    }
        return [
            'code' => $this->getCode(),
            'title' => $this->helper->getTitle(),
            'value' => $amount
        ];
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->helper->getTitle());
    }
}
