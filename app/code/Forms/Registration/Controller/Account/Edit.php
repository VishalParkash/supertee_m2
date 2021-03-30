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


class Edit extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_dataExample;
    protected $resultRedirect;
        protected $_redirect;
    protected $_url;
    protected $customerRegistry;
    protected $customerRepository;
    protected $encryptor;

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

    public function execute()
    {
      //  print_r('haha');die();
        $post = (array) $this->getRequest()->getPost();
        if (!empty($post)) {
            $customerId = $this->_customerSession->getCustomer()->getId();
            $name = explode(' ', $_POST['name']);
            $firstname = $name[0];
            $lastname = (isset($name[1]))?$name[1]:'';
            $lastname .= (isset($name[2]))?' '.$name[2]:'';
            $mobile = $_POST['mobile'];
            $gender = $_POST['gender'];
            $country = $_POST['country'];
            $city = $_POST['city'];
            $statecode = $_POST['statecode'];
            $street = $_POST['street'];
            $billingId = $_POST['billingId'];
                    $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
                    $objectManager = $bootstrap->getObjectManager();
                    $appState = $objectManager->get('\Magento\Framework\App\State');
                    $customerRepositoryInterface = $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface');
                    $customerRegistry = $objectManager->get('\Magento\Customer\Model\CustomerRegistry');
                    $encryptor = $objectManager->get('\Magento\Framework\Encryption\EncryptorInterface');
                    $appState->setAreaCode('frontend');
                    $customerId = $this->_customerSession->getCustomer()->getId(); // here assign your customer id
                    $customer = $customerRepositoryInterface->getById($customerId); // _customerRepositoryInterface is an instance of \Magento\Customer\Api\CustomerRepositoryInterface
                    $customer->setFirstname($firstname);
                    $customer->setLastname($lastname);
                    $customer->setGender($gender);
                    $customerRepositoryInterface->save($customer);
                    // Save country state
                    $addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
                    if (!empty($billingId)) {$address = $addresss->create()->load($billingId);}else{$address = $addresss->create();}
                    $address->setCustomerId($customerId)
                            ->setTelephone($mobile)
                            ->setCity($city)
                            ->setCountryId($country)
                            ->setPostcode($statecode)
                            ->setLastname($lastname)
                            ->setStreet($street)
                            ->setFirstname($firstname)
                            ->setIsDefaultBilling('1')
                            ->setSaveInAddressBook('1');
                    $address->save();
                    $response = [
                        'errors' => false,
                        'message' => 'Your Profile updated successfully.'
                    ];
                
            $response_val = json_encode($response);
            echo $response_val;
        }
    }
}