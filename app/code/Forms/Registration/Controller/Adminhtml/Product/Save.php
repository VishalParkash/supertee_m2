<?php
namespace Forms\Registration\Controller\Adminhtml\Product;

class Save extends \Magento\Catalog\Controller\Adminhtml\Product
{

    public function execute(){
	    $productId = $this->getRequest()->getParams();
	    $data = $this->getRequest()->getPostValue();

	    print_r($_POST);die();
	    return parent::execute();
    }
}