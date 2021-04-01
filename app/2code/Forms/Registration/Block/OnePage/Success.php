<?php
namespace Forms\Registration\Block\OnePage;
class Success extends \Magento\Framework\View\Element\Template
{
    public function getCustomSuccess()
    {
        unset($_SESSION['discountVar']);
    }
} 