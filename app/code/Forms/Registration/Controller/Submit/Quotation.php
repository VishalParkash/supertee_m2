<?php
namespace Forms\Registration\Controller\Submit;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\JsonFactory;

// use Magento\Framework\Math\Random;

// use \Magento\Customer\Model\Session;
// use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;


class Quotation extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultRedirect;
    protected $_redirect;
    protected $_url;
    protected $customer;
    protected $messageManager;
    protected $resource;
    protected $connection;
    protected $resultJsonFactory;
    protected $scopeConfig;


     public function __construct(\Magento\Framework\App\Action\Context $context,
        ManagerInterface $messageManager,
        ResourceConnection $resource,
        \Magento\Framework\Math\Random $mathRandom,
        // \Forms\Registration\Model\DataExampleFactory  $dataExample,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Customer\Model\Session $customer,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
        \Forms\Registration\Model\Session $session,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->mathRandom = $mathRandom;
        // $this->session = $session;
        $this->messageManager       = $messageManager;
        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();
        // $this->_dataExample = $dataExample;
        $this->_filesystem = $fileSystem;
        $this->_pageFactory = $pageFactory;
        $this->customer = $customer;
        $this->directory_list = $directory_list; 
        $this->_url = $url;
         $this->_redirect = $redirect; 
         $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRedirect = $result;
        $this->scopeConfig = $scopeConfig;



    }

    public function getStoreEmail()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

    public function execute(){
// echo $this->getStoreEmail();die;
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->_pageFactory->create();

        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {
            try {
                $fullName = (isset($post['fullName'])) ? $post['fullName'] : 'Not entered';
                $emailAddress = (isset($post['emailAddress'])) ? $post['emailAddress'] : 'info@supertee.com';
                $phoneNumber = (isset($post['phoneNumber'])) ? $post['phoneNumber'] : 'Not entered';
                $quantity = (isset($post['quantity'])) ? $post['quantity'] : 'Not entered';
                $productDetail = (isset($post['productDetail'])) ? $post['productDetail'] : 'Not entered';
                $projectDescription = (isset($post['projectDescription'])) ? $post['projectDescription'] : 'Not entered';

                $themeTable = $this->connection->getTableName('supertee_quotations');

                if(!empty($fullName) && (!empty($emailAddress)) && (!empty($phoneNumber)) && (!empty($quantity)) && (!empty($productDetail)) && (!empty($projectDescription))){
                    $sql = "INSERT INTO " . $themeTable . "(fullName, emailAddress, phoneNumber, quantity, productDetail, projectDescription) VALUES ('".$fullName."', '".$emailAddress."','".$phoneNumber."', '".$quantity."', '".$productDetail."','".$projectDescription."' )";
                    $this->connection->query($sql);  
                    // $recipientMail = 'vishal.parkash@cerebrent.com';
                    $recipientMail = "supertee.admin@mailinator.com";

                    $msg = '';
                    
                    $msg .= "<p>Hi</p>";
                    $msg .= "<p>Someone has requested quotation for the products. </p>";
                    $msg .= "<p>Here are the detais.</p>";
                    $msg .= "<p>Full Name: ".$fullName."</p>";
                    $msg .= "<p>Email Address: ".$emailAddress."</p>";
                    $msg .= "<p>Phone Number: ".$phoneNumber."</p>";
                    $msg .= "<p>Quantity: ".$quantity."</p>";
                    $msg .= "<p>Product Detail: ".$productDetail."</p>";
                    $msg .= "<p>Project Description: ".$projectDescription."</p>";
                    $msg .= "<p>You are referred by your friend to join Supertee</p>";
                    $msg .= "<p>Regards</p>";
                    
                    // echo $msg;die;
                    $email = new \Zend_Mail();
                    $email->setSubject("Quotation Requested");
                    $email->setBodyHtml($msg);
                    $email->setFrom($emailAddress, "Quotation Requested");
                    $email->addTo($recipientMail, "Friend");
                    if($email->send()){
                        $output = true;
                    }  
                }else{
                    $output = "emptyData";
                }
            } catch (Exception $e) {
                \Zend_Debug::dump($e->getMessage());
                $$output = false;
            }

            $result->setData(['output' => $output]);
            return $result;

            // $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            // $resultRedirect->setPath();

            // return $resultRedirect;
        }
    }
}