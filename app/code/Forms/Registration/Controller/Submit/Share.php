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


class Share extends \Magento\Framework\App\Action\Action
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
            
                $customer = $this->customer;

                $customerId = $customer->getId();
                $referralUserEmail = $customer->getCustomer()->getEmail();
                $referralUserName = $customer->getCustomer()->getName();

                $themeTable = $this->connection->getTableName('user_rewards');


                //$themeTable2 = $this->connection->getTableName('user_rewards');
                 // $sql = "INSERT INTO " . $themeTable2 . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customerId.", ".$donation_points.", 'debit',  'forDonation')";
                // $this->connection->query($sql);

                $sharingMedia = $post['sharingMedia'];

                $checkShare = "SELECT * FROM " . $themeTable . " WHERE customer_id ='".$customerId."' AND rewards_points_id='".$sharingMedia."'";
                $result = $this->connection->fetchAll($checkShare);

                if(!empty($result)){
                    
                    $response = "You have already shared the url on facebook";
                    
                    
                }else{
                    $sql = "INSERT INTO " . $themeTable . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customerId.", '200', 'credit',  'fbShare')";
                    $this->connection->query($sql);
                    $response = true;
                }
                return $resultJson->setData($response);
            }

        
    }
}