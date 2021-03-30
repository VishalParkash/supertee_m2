<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forms\Registration\Controller\Search;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var ScopeConfigInterface
     */

    protected $request;

    protected $connection;
    protected $productCollectionFactory;
    protected $_pageFactory;


    /**
     * Initialize Login controller
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Json\Helper\Data $helper
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_pageFactory = $pageFactory;
        $this->connection = $resource->getConnection();
        
    }


    public function execute()
{
        $resultPage = $this->_pageFactory->create();
        $search_text = $this->getRequest()->getPost('search_text');

        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(array('name'))->addAttributeToFilter('name',
        array('like' => $search_text.' %'),
        array('like' => '% '.$search_text.' %'), 
        array('like' => '% '.$search_text) 
        );

        // if(!empty($collection)){
        if(!empty($collection->getData())){
            $response = $collection->getData();
               
        }else{
            $response = array();
        }
       //  echo "<pre>";
       //  print_r($collection->getData());
       // die();


        $block = $resultPage->getLayout()
                ->createBlock('Forms\Registration\Block\Index\View')
                ->setTemplate('Forms_Registration::search.phtml')
                ->setData('data',$response)
                ->toHtml();
        
        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(['output' => $block]);
        return $resultJson;



       
        // return $resultJson->setData($response);
}


    // public function execute(){

    //     $post = (array) $this->getRequest()->getPost();
    //     // echo "<pre>";print_r($post);die;

    //     $pincode = $post['pincode'];
    //     $pincode_checker = $this->connection->getTableName('pincode_checker');   
    //     $pincode_data = $this->connection->fetchAll("SELECT * FROM ".$pincode_checker." WHERE pincode=".$pincode);

    //     if(!empty($pincode_data)){
    //         $response =  true;
    //     }else{
    //         $response = false;
    //     }


    //     // if(!empty($result)){
    //     //     $response['status']  = true;
    //     //     $response['msg']  = "success";
    //     //     $response['result']  = $imageUploaded;
    //     // } 
            
    //     /** @var \Magento\Framework\Controller\Result\Json $resultJson */
    //     $resultJson = $this->resultJsonFactory->create();
    //     return $resultJson->setData($response);
    //     }
        
    

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
}

}