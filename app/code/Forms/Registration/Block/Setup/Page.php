<?php
namespace Forms\Registration\Block\Setup;
use Magento\Backend\Block\Template\Context;
class Page extends \Magento\Framework\View\Element\Template
{
	public function __construct(Context $context,array $data = [])
    {
        parent::__construct($context, $data);
    }
    public function getFormAction()
        {
        return $this->getUrl('postvendor/submit/submit', ['_secure' => true]);  
    }
}