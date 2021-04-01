<?php

namespace Forms\Registration\Controller\Checkout;
use Magento\Framework\App\ResourceConnection;
// use \Magento\Framework\Event\ObserverInterface;

/**
 * Class CustomPrice
 * @package Aureatelabs\CustomPrice\Observer
 *
 */
class Update extends \Magento\Framework\App\Action\Action
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
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->session = $session;
        $this->connection = $resource->getConnection();

    }


    public function execute() {
        $post = (array) $this->getRequest()->getPost();

        $result = $this->resultJsonFactory->create();

        if($this->session->setData("discountVar", $post['action'])){
                    $result->setData(['output' => true]);    
                }else{
                    $result->setData(['output' => false]);
                }
        return $result;

            // echo $quoteId = $post['quoteId'];
            // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            // $cartsess = $objectManager->get('Magento\Checkout\Model\Session');
            // $cartsess->setQuoteId($quoteId);
            // $cartsess->setLoadInactive(true);
            // $quote = $cartsess->getQuote();
            // $quote->setIsActive(true);
            
            
            //  $customSubtotal = 100; //You can set your custom subtotal amount
            //  $customGrandTotal = 200; //You can set your custom Grand total amount
            //  $updatedSubtotal = $quote->setSubtotal($customSubtotal); 
            //  $updatedGrandTotal = $quote->setGrandTotal($customGrandTotal);  


            //  $quote->collectTotals();

            // $quote->setTriggerRecollect(1); //Magic line.
            
            // // $quote->save();
            // $quote->collectTotals()->save();
            // $quote->setTotalsCollectedFlag(false)->collectTotals();
            // $this->_messangeManager->addSuccess('Product is successfully added in to cart.');   
            // $redirectUrl = $this->_cartHelper->getCartUrl();
            // $observer->getControllerAction()->getResponse()->setRedirect($redirectUrl);

            die;
    //     $quoteId = intval($this->_request->getParam('quoteId'));
    //     echo "<pre>";print_r($quoteId);die;

    // $item = $observer->getEvent()->getQuoteItem();
    // // $item = $observer->getEvent()->getData('quote_item');
    // $item->getQuote()->collectTotals();
    //  // echo "<pre>";
    //  $postData = $this->request->getParams('newPrice');
    //  // print_r($newPrice['newPrice']);
    //  $newPrice = $postData['newPrice'];
    //  // $customSubtotal = 100; //You can set your custom subtotal amount
    //  // $customGrandTotal = 200; //You can set your custom Grand total amount
    //  $updatedSubtotal = $item->getQuote()->setSubtotal($newPrice); 
    //  $updatedGrandTotal = $item->getQuote()->setGrandTotal($newPrice);
    //  $item->setCustomPrice($newPrice);
    //  $item->setOriginalCustomPrice($newPrice);
    //  $item->getProduct()->setIsSuperMode(true);


        // $item = $observer->getEvent()->getData('quote_item');

        // // Get parent product if current product is child product
        // $item = ( $item->getParentItem() ? $item->getParentItem() : $item );

        // // echo "<pre>";print_r($item->getData());
        // // $post = (array) $this->getRequest()->getPost();
        // // $post = $this->request->getPost();
        // // $reqeustParams = $this->request->getParams('updatedPrice');

        
        
        // // // die;

        // // //Define your Custom price here
        // // $price = ($post['updatedPrice']);
     
        // // //Set custom price
        // // $item->setCustomPrice($price);
        // // $item->setOriginalCustomPrice($price);
        // // $item->getProduct()->setIsSuperMode(true);
    }
}