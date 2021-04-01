<?php
namespace Forms\Registration\Controller\Submit;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResourceConnection;
// use Magento\Framework\Math\Random;

// use \Magento\Customer\Model\Session;
// use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;


class Refer extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultRedirect;
    protected $_redirect;
    protected $_url;
    protected $customer;
    protected $messageManager;
    protected $resource;
    protected $connection;


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
        JsonFactory $resultJsonFactory,
        \Forms\Registration\Model\Session $session,

    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->mathRandom = $mathRandom;
        $this->session = $session;
        $this->messageManager       = $messageManager;
        $this->resource             = $resource;
        $this->connection           = $resource->getConnection();
        // $this->_dataExample = $dataExample;
        $this->_filesystem = $fileSystem;
        $this->customer = $customer;
        $this->directory_list = $directory_list; 
        $this->_url = $url;
         $this->_redirect = $redirect; 
        $this->resultRedirect = $result;
        $this->resultJsonFactory = $resultJsonFactory;


    }

    public function getRandomString($length,  $chars = null)
    {
        return $this->mathRandom->getRandomString($length, $chars);
    }

    // public function __construct(
    //  \Magento\Framework\App\Action\Context $context,
    //  \Magento\Framework\View\Result\PageFactory $pageFactory)
    // {
    //  $this->_pageFactory = $pageFactory;
    //  return parent::__construct($context);
    // }

    /** @return string */
function getMediaBaseUrl() {
/** @var \Magento\Framework\ObjectManagerInterface $om */
$om = \Magento\Framework\App\ObjectManager::getInstance();
/** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
$storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
/** @var \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store $currentStore */
$currentStore = $storeManager->getStore();
return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
}

function getBaseUrl(){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
    return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
}



    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {

            


            // if (!empty($_POST['referFriend'])) {
            try {
                // $this->session->setData("donation_data", $post);
                $customer = $this->customer;
                // echo "<pre>";print_r($customer->getCustomer()->getEmail());die;
                // $customerName = $customer->getName();
                // $customerName = $customer->getCustomer()->getName();
                $referralUserId = $customer->getId();
                $referralUserEmail = $customer->getCustomer()->getEmail();
                $referralUserName = $customer->getCustomer()->getName();
                $recipient_email = $post['recipient_email'];
                $randomString =  $this->getRandomString(5);

                $referralLink = $this->getBaseUrl()."?referid=".$randomString;

                $themeTable = $this->connection->getTableName('referral_system');

                $checkReferral = "SELECT * FROM " . $themeTable . " WHERE recipient_email ='".$recipient_email."' ORDER BY id DESC LIMIT 0,1";
                $result = $this->connection->fetchAll($checkReferral);

                if(!empty($result)){
                    foreach($result as $collection){
                        if($collection['signUp_status'] == 1){
                            $referralMsg = "This user is already registered. Please refer other friend.";
                        }elseif($collection['signUp_status'] == 0){
                            $referralMsg = "This user is already referred by some other user. Please refer other friend.";
                        }
                        // $customer->setMyMessage($referralMsg);
                        return $resultJson->setData($referralMsg);

                    }
                }

                $sql = "INSERT INTO " . $themeTable . "(user_id, recipient_email, tokenCode) VALUES ('".$referralUserId."', '".$recipient_email."','".$randomString."' )";
                $this->connection->query($sql);
                // die;
                $msg = '';
                $msg .= "<p>Hi</p>";
                $msg .= "<p>You are referred by your friend to join Supertee</p>";
                $msg .= "<p>You have been referred by your friend ".$referralUserName." to join Supertee.</p>";
                $msg .= "<p>Here you can customise the products online and get it delivered at your door step.</p>";
                $msg .= "<p>Please click the below given link to join the application.</p>";
                $msg .= "<p><a href='".$referralLink."' target='_blank'>Click here to join Supertee.</a></p>";
                $msg .= "<p>You are referred by your friend to join Supertee</p>";
                $msg .= "<p>Regards</p>";
                $msg .= "<p>Team Supertee</p>";
                // echo $msg;die;
                $email = new \Zend_Mail();
                $email->setSubject("You are referred by your friend to join Supertee");
                $email->setBodyHtml($msg);

                $email->setFrom($referralUserEmail, "Supertee");
                $email->addTo($recipient_email, "Friend");
                if($email->send()){
                    $response =true;
                }else{
                    $response = false;
                }
                
            } catch (Exception $e) {
                $response = false;
                // \Zend_Debug::dump($e->getMessage());
            }
            
            // $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData($response);

        }
    }
}