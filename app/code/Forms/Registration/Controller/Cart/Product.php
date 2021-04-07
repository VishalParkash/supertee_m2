<?php 
namespace Forms\Registration\Controller\Cart;

use Magento\Framework\Controller\ResultFactory;
class Product extends \Magento\Framework\App\Action\Action{


protected $_cart;
protected $productRepository;
protected $_redirect;
protected $_url;
protected $productAttributeRepository;

public function __construct(\Magento\Framework\App\Action\Context $context,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    \Magento\Framework\UrlInterface $url,
    \Magento\Framework\App\Response\Http $redirect,
    \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository

)
{   parent::__construct($context);
    $this->_cart = $cart;
    $this->productRepository = $productRepository;
    $this->_url = $url;
    $this->_redirect = $redirect;
    $this->productAttributeRepository = $productAttributeRepository;

}

    public function execute() {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');

        $post = (array) $this->getRequest()->getPost();
        // echo "<pre>";print_r($post);die;
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);


        $productQuantity = $post['productQuantity'];
        foreach ($productQuantity as $key => $value) {
            $getProductId = explode("_", $key);
            $productId = ($getProductId[1]);
            $attribute = ($getProductId[0]);
            if(!empty($value)){
                $qty = ($value); 

                $attribute = $this->productAttributeRepository->get('size');
                $sizeId = $attribute->getAttributeId();

                $options = array(
                        $sizeId => $attribute
                    );

                $params = array(
                    'product' => $productId,
                    'super_attribute' => $options,
                    'qty' => $qty
                );
            $_product = $this->productRepository->getById($productId);
            $this->_cart->addProduct($_product,$params);
            $this->_cart->save();
   
            }
            
            
        }
        //session_start();
        $_SESSION["cart_success"] = "Item added to cart successfully";
        header("Location:".$request->getServer('HTTP_REFERER'));
        die;

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;


        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;


        // $CustomRedirectionUrl = $this->_url->getUrl();
        // $this->_redirect->setRedirect($CustomRedirectionUrl);
        // return;
        // die;
        // $productId = 377; // enter your product_id
        // $qty = 11; // enter number of quantites you want to add

        /*
        I am using below ids as configurable options for example purpose only.
        You have to use your attribute id and option id.

        90 = attribute_id of color 
        53 = option_id of any specific color,

        143 = attribute_id of size
        170 = option_id of any specific size
        */

        
    }
}