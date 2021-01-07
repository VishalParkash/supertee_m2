<?php
namespace Forms\Registration\Block\Quote;
use Magento\Backend\Block\Template\Context;
class Request extends \Magento\Framework\View\Element\Template
{
	public function __construct(Context $context,array $data = [])
    {
        parent::__construct($context, $data);
    }
   
}