<?php
declare(strict_types=1);

namespace Forms\Registration\Block\Adminhtml\Product;

/**
 * Class Custom
 *  * @package Vendor\Module\Block\Adminhtml\Product;
 */
class Custom extends \Magento\Backend\Block\Template
{
    public function customContent()
    {
        return 'custom content from block';
    }
}