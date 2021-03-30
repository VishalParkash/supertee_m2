<?php
namespace Forms\Registration\Controller\Account;
use Magento\Framework\Controller\ResultFactory;
use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Framework\Translate\Inline\StateInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerAuthUpdate;
use Magento\Backend\App\ConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\State\UserLockedException;
class Change extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_dataExample;
    protected $resultRedirect;
        protected $_redirect;
    protected $_url;
    protected $customerRegistry;
    protected $customerRepository;
    protected $encryptor;
    protected $resultJsonFactory;
     public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Framework\Encryption\EncryptorInterface $encryptor,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
         $this->_redirect = $redirect;
        $this->resultRedirect = $result;
        $this->_customerRegistry   = $customerRegistry;
        $this->_CustomerRepositoryInterface = $customerRepository;
        $this->_customerSession = $customerSession;
        $this->encryptor = $encryptor;


    }


    // public function __construct(
    //  \Magento\Framework\App\Action\Context $context,
    //  \Magento\Framework\View\Result\PageFactory $pageFactory)
    // {
    //  $this->_pageFactory = $pageFactory;
    //  return parent::__construct($context);
    // }

    /** @return string */
// function getMediaBaseUrl() {
// /** @var \Magento\Framework\ObjectManagerInterface $om */

// return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
// }

    public function execute()
    {
        //print_r('hello priyank lohan');die();
        $post = (array) $this->getRequest()->getPost();

        // echo "<pre>";print_r($post);die;
        if (!empty($post)) {
            
            $current_password   = $post['current_password'];
            $new_password    = $post['new_password'];
            $confirm_password    = $post['confirm_password'];
            $current_user_pass_hash = $this->_customerSession->getCustomer()->getPasswordHash();
            if ($this->encryptor->validateHash($current_password, $current_user_pass_hash)) {
                if($new_password !=$confirm_password){
                    $response = [
                        'errors' => true,
                        'message' => 'New Password and confirm password does not matched'
                    ];
                    // $this->messageManager->addError(__('New Password and confirm password does not matched')); 
                }else{
                    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
                    $objectManager = $bootstrap->getObjectManager();
                    $appState = $objectManager->get('\Magento\Framework\App\State');
                    $customerRepositoryInterface = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');
                    $customerRegistry = $objectManager->get('\Magento\Customer\Model\CustomerRegistry');
                    $encryptor = $objectManager->get('\Magento\Framework\Encryption\EncryptorInterface');
                    $appState->setAreaCode('frontend');
                    $customerId = $this->_customerSession->getCustomer()->getId(); // here assign your customer id
                    $customer = $customerRepositoryInterface->getById($customerId); // _customerRepositoryInterface is an instance of \Magento\Customer\Api\CustomerRepositoryInterface
                    $customerSecure = $customerRegistry->retrieveSecureData($customerId); // _customerRegistry is an instance of \Magento\Customer\Model\CustomerRegistry
                    $customerSecure->setRpToken(null);
                    $customerSecure->setRpTokenCreatedAt(null);
                    $customerSecure->setPasswordHash($encryptor->getHash($confirm_password, true)); // here _encryptor is an instance of \Magento\Framework\Encryption\EncryptorInterface
                    $customerRepositoryInterface->save($customer);
                    $response = [
                        'errors' => false,
                        'message' => 'Your Password is successfully change.'
                    ];
                }

            }else{
                //$this->messageManager->addSuccess(__('Current password does not matched.'));
                $response = [
                    'errors' => true,
                    'message' => 'Current password does not matched.'
                ];
            }
            $response_val = json_encode($response);
            echo $response_val;
            //$resultJson = $this->resultJsonFactory->create();
            //return $resultJson->setData($response);

            //$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            //$resultRedirect->setPath("/customer/account/password");
            //return $resultRedirect->setPath("/customer/account/password");
        }
    }
}