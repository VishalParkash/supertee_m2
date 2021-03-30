<?php

namespace Forms\Registration\Block\Adminhtml\Product;
use \Magento\Catalog\Api\ProductRepositoryInterfaceFactory;

class Store extends \Magento\Backend\Block\Template
{
    protected $_registry;
    protected $request;
 
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;
        $this->request = $request;
        parent::__construct($context);
    }

    public function getCurrentProduct()
    {
        // return $this->request->getParams();
        return $this->_registry->registry('current_product');
    }

    public function getStoreProduct(){
        $product_id = $this->getCurrentProduct()->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $themeTable = $connection->getTableName('storeProduct');

        $getStores = "SELECT * FROM " . $themeTable . " WHERE product_id = '".$product_id."'  ORDER BY id ASC";
        $response = $connection->fetchAll($getStores);


        // echo "<pre>";print_r($response);die;

        if(!empty($response)){
            foreach($response as $res){
                $storeProduct[] = $res['typeofStore_id'];
            }
        }
        if(!empty($storeProduct)){
            return $storeProduct;    
        }
        
        
    }

    public function getStores(){
        $product_id = $this->getCurrentProduct()->getId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $themeTable = $connection->getTableName('typeOfStore');

        $getStores = "SELECT * FROM " . $themeTable." ORDER BY id ASC";
        $response = $connection->fetchAll($getStores);

        return $response;


        // echo "<pre>";print_r($response);die;

        // if(!empty($response)){
        //     foreach($response as $res){
        //         $storeProduct[] = $res['typeofStore_id'];
        //     }
        // }

        // return $storeProduct;
        
    }
}