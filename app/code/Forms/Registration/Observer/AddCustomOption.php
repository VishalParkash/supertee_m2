<?php

namespace Forms\Registration\Observer;

use Magento\Framework\Event\ObserverInterface;
use Forms\Registration\Model\CoordinatesFactory;

class AddCustomOption implements ObserverInterface
{
    protected $_options;
    protected $_CoordinatesFactory;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\Product\Option $options,
        CoordinatesFactory $CoordinatesFactory
    ) {
        $this->_options = $options;
        $this->_CoordinatesFactory = $CoordinatesFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // $_product = $observer->getProduct();  // you will get product object
        // $productId = $this->getRequest()->getParams();
        // $data = $this->getRequest()->getPostValue();
        // echo "<pre>";print_r($_POST['product']['current_product_id']);
        // echo "<pre>";print_r($_POST);die;
        $CoordinatesModel          = $this->_CoordinatesFactory->create();
        if(
            !empty($_POST['canvasHeight']) ||
            !empty($_POST['canvasWidth'])  ||
            !empty($_POST['canvasXCdnt'])  ||
            !empty($_POST['canvasYCdnt'])
    ){
            $productId = ($_POST['product']['current_product_id']);
            $canvasHeight = ($_POST['canvasHeight']);
            $canvasWidth = ($_POST['canvasWidth']);
            $canvasXCdnt = ($_POST['canvasXCdnt']);
            $canvasYCdnt = ($_POST['canvasYCdnt']);
            // print_r($data);
            $CoordinatesModel->setData('product_id', $productId);
            $CoordinatesModel->setData('canvasHeight', $_POST['canvasHeight']);
            $CoordinatesModel->setData('canvasWidth', $_POST['canvasWidth']);
            $CoordinatesModel->setData('xCoordinate', $_POST['canvasXCdnt']);
            $CoordinatesModel->setData('yCoordinate', $_POST['canvasYCdnt']);
            $CoordinatesModel->save();
        }
    }

    // public function execute(\Magento\Framework\Event\Observer $observer)
    // {
    //     $product = $observer->getProduct();
    //     $options = [];
    //     $options = [
    //         '0' => [
    //                 'sort_order' => '1',
    //                 'title' => 'option title',
    //                 'price_type' => 'fixed',
    //                 'price' => '5',
    //                 'type' => 'drop_down',
    //                 'is_require' => '0',
    //                 'values' => [
    //                     '0' =>[
    //                             'title' => 'A',
    //                             'price' => '50',
    //                             'price_type' => 'fixed',
    //                             'sku' => 'A',
    //                             'sort_order' => '0',
    //                             'is_delete' => '0',
    //                     ],
    
    //                     '1' => [
    //                             'title' => 'B',
    //                             'price' => '100',
    //                             'price_type' => 'fixed',
    //                             'sku' => 'B',
    //                             'sort_order' => '1',
    //                             'is_delete' => '0',
    //                     ],
    
    //                     '2' => [
    //                             'title' => 'C',
    //                             'price' => '150',
    //                             'price_type' => 'fixed',
    //                             'sku' => 'C',
    //                             'sort_order' => '2',
    //                             'is_delete' => '0',
    //                     ]
    //                 ]
    //             ]
    //         ];
    //     foreach ($options as $arrayOption) {
    //         $option = $this->_options
    //                         ->setProductId($product->getId())
    //                         ->setStoreId($product->getStoreId())
    //                         ->addData($arrayOption);
    //         $option->save();
    //         $product->addOption($option);
    //     }        
    // }
}