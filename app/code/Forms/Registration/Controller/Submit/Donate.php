<?php
namespace Forms\Registration\Controller\Submit;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResourceConnection;
// use Magento\Framework\Math\Random;

// use \Magento\Customer\Model\Session;
// use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;


class Donate extends \Magento\Framework\App\Action\Action
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
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->mathRandom = $mathRandom;
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

        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {

            


            // if (!empty($_POST['referFriend'])) {
            try {
                $customer = $this->customer;
                // echo "<pre>";print_r($customer->getCustomer()->getEmail());die;
                // $customerName = $customer->getName();
                // $customerName = $customer->getCustomer()->getName();
                // echo "<pre>";print_r($post);die;
                $customerId = $customer->getId();
                $UserEmail = $customer->getCustomer()->getEmail();
                $UserName = $customer->getCustomer()->getName();
                $organisation_name = $post['organisation_name'];
                $contact_person = $post['contact_person'];
                $donation_points = $post['donation_points'];

                $donation_amt = $donation_points*(0.02);
                // $donation_amt = $post['donation_amt'];
                $org_email = $post['org_email'];
                $org_phone = $post['org_phone'];
                $org_address = $post['org_address'];
                $donation_notes = $post['donation_notes'];
                $randomString =  $this->getRandomString(5);

                $referralLink = $this->getBaseUrl()."?referid=".$randomString;

                $themeTable = $this->connection->getTableName('superTee_donations');

                $checkReferral = "SELECT * FROM " . $themeTable . " WHERE donated_to ='".$organisation_name."' AND donated_by = '".$customerId."'";
                $result = $this->connection->fetchAll($checkReferral);

                $sql = "INSERT INTO " . $themeTable . "(donated_by, donated_to, donation_points, donation_amt, contact_person, org_email, org_phone, org_address, donation_notes) VALUES ('".$customerId."', '".$organisation_name."','".$donation_points."', '".$donation_amt."','".$contact_person."', '".$org_email."','".$org_phone."','".$org_address."','".$donation_notes."' )";
                $this->connection->query($sql);

                $themeTable2 = $this->connection->getTableName('user_rewards');
                 $sql = "INSERT INTO " . $themeTable2 . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customerId.", ".$donation_points.", 'debit',  'forDonation')";
                $this->connection->query($sql);
                // die;
                // $msg = '';
                // $msg .= "<p>Hi</p>";
                // $msg .= "<p>".$UserName." has donated funds.</p>";
                // $msg .= "<p>Regards</p>";
                // $msg .= "<p>Team Supertee</p>";
                // $email = new \Zend_Mail();
                // $email->setSubject("Donation Recieved");
                // $email->setBodyHtml($msg);
                // $email->setBodyText("You have been referred by your friend ".$referralUserName." to join Supertee.");
                // $email->setBodyText("Here you can customise the products online and get it delivered at your door step.");
                // $email->setBodyText("Please click the below given link to join the application.");
                // $email->setBodyText("hope you are good");
                // $email->setFrom($UserEmail, $UserName);
                // $email->addTo($org_email, $organisation_name);
                // $email->send();
                
            } catch (Exception $e) {
                \Zend_Debug::dump($e->getMessage());
            }
            
            $CustomRedirectionUrl = $this->_url->getUrl();
            $this->_redirect->setRedirect($CustomRedirectionUrl);
            return;

            // $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            // $resultRedirect->setPath();

            return $resultRedirect;
        }
    }
}