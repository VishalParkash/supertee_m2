<?php

namespace Forms\Registration\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{    
    /**
     * Custom factory
     *
     * @var \Vendor\ModuleName\Model\CustomProductsFactory
     */
    // protected $_CustomProductsFactory;

    /**
     * Http Request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;


    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        // \Forms\Registration\Model\CustomProductsFactory $customProductsFactory,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        // $this->_CustomProductsFactory = $CustomProductsFactory;
        $this->request = $request;
    }


    /**
     *
     *  @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();

        if ((!empty($product))) {

        	// $exampleMultiSelectField = $product->getData('typeofstore');
        		// echo "<pre>";print_r($exampleMultiSelectField);die;
        	// foreach($product as $pr){
        	// 	echo "<pre>";print_r($exampleMultiSelectField());die;
        	// }
        	
            $productId = $product->getId();
            // $requestId = $this->request->getParam('id');
            echo $productName =  $product->getName();
            // $customFieldSetData = serialize($product->getCustomFieldSet());
            // echo "<pre>";print_r($customFieldSetData);die;

            $customFieldValue =  $this->request->getPost('typeofstore');
            echo "<pre>";print_r($customFieldValue);die;
			$product->setNewAttribute($exampleMultiSelectField); 
			echo "<pre>";print_r($customFieldValue);die;
			// $product->save();
			// die;
            // if ($productId) {
            //     $this->saveCustomProductData($requestId,$productId,$customFieldSetData);
            // }
        }
    }


    // public function saveCustomProductData($requestId,$productId,$customFieldSetData)
    // {

    //     $model = $this->_CustomProductsFactory->create();

    //     if (empty($requestId)) {
    //         $data = ['typeofstore'=>$customFieldSetData,'product_id'=>$productId];
    //     } 
    //     // else {
    //     //     //load model
    //     //     $model->load($productId, 'product_id');
    //     //     $data = ['typeofstore'=>$customFieldSetData,'product_id'=>$productId];
    //     // }

    //     $model->addData($data);

    //     $model->save();
    // }  
}