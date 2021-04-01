<?php
namespace Forms\Registration\Block\Product;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class CustomBtn extends Template{

	protected $registry;
	public function __construct(
		Template\Context $context,
		Registry $registry,
		array $data = []){
		parent::__construct($context, $data);
		$this->registry = $registry;
	}

	public function getCurrentProductId(){
		$product = $this->getCurrentProduct();
		echo "<pre>";print_r($product);
			die;
		// foreach ($product as  $value) {
			
		// }
	}

	public function getCurrentProduct(){
		return $this->registry->registry('product');
	}
}