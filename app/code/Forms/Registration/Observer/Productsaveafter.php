<?php

namespace Forms\Registration\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection;

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
    protected $connection;


    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Framework\App\Request\Http $request
     * @param array $data
     */
    public function __construct(
        // \Forms\Registration\Model\CustomProductsFactory $customProductsFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        array $data = []
    ) {
        // $this->_CustomProductsFactory = $CustomProductsFactory;
        $this->request = $request;
        $this->connection = $resource->getConnection();

    }


    /**
     *
     *  @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        // die('vishal');
        // echo "<pre>";print_r($product->getData());
        // echo "<pre>";print_r($_REQUEST);


// die;
        
        // echo "productId ".$_REQUEST['product']['current_product_id'];
        // echo "selectableCordLeft ".$_REQUEST['selectableCordLeft'];
        // echo "selectableCordTop ".$_REQUEST['selectableCordTop'];
        // $_REQUEST['selectableCordLeft'];
        // $_REQUEST['selectableCordTop'];
        // $_REQUEST['side'];

        
        if(!empty($_REQUEST['loopingCount'])){
            $loopingCount = $_REQUEST['loopingCount'];
            $previosproduct = "DELETE from productCoordinates WHERE product_id = ".$_REQUEST['product']['current_product_id'];
        $this->connection->query($previosproduct);
            for($i=0;$i<$loopingCount;$i++){


        $selectableCordTop = 'selectableCordTop_'.$i;
        $selectableCordLeft = 'selectableCordLeft_'.$i;
        $height = 'height_'.$i;
        $width = 'width_'.$i;
        $productSide = 'productSide_'.$i;
        $getImgUrl = 'getImgUrl_'.$i;

        

        $productCoordinates = "INSERT INTO productCoordinates(product_id, side, top_crdnt, left_crdnt, width, height, url) VALUES ('".$_REQUEST['product']['current_product_id']."', '".$_REQUEST[$productSide]."', '".$_REQUEST[$selectableCordTop]."', '".$_REQUEST[$selectableCordLeft]."',  '".$_REQUEST[$width]."',  '".$_REQUEST[$height]."', '".$_REQUEST[$getImgUrl]."')";

        
            $this->connection->query($productCoordinates);
    }
        }
        

        // die;
   //      if ((!empty($product))) {
   //          die('vishal');

   //      	// $exampleMultiSelectField = $product->getData('typeofstore');
   //      		// echo "<pre>";print_r($exampleMultiSelectField);die;
   //      	// foreach($product as $pr){
   //      	// 	echo "<pre>";print_r($exampleMultiSelectField());die;
   //      	// }
        	
   //          // $productId = $product->getId();
   //          // $requestId = $this->request->getParam('id');
   //          // echo $productName =  $product->getName();
   //          // $customFieldSetData = serialize($product->getCustomFieldSet());
   //          // echo "<pre>";print_r($customFieldSetData);die;

   // //          $customFieldValue =  $this->request->getPost('typeofstore');
   // //          echo "<pre>";print_r($customFieldValue);die;
			// // $product->setNewAttribute($exampleMultiSelectField); 
			// // echo "<pre>";print_r($customFieldValue);die;
			// // $product->save();
			// // die;
   //          // if ($productId) {
   //          //     $this->saveCustomProductData($requestId,$productId,$customFieldSetData);
   //          // }
   //      }
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