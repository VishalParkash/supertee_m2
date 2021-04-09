<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ManishJoy\CustomerLogin\Controller\Ajax;

use Magento\Framework\Exception\LocalizedException;

/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Register extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Session\Generic
     */
    protected $session;

    /**
     * @var \Magento\Framework\Json\Helper\Data $helper
     */
    protected $helper;

    /**
     * @var \ManishJoy\CustomerLogin\Model\Customer $customerModel
     */
    protected $customerModel;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $request;

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
        \Magento\Customer\Model\Session $customerSession,
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Json\Helper\Data $helper,
        \ManishJoy\CustomerLogin\Model\Customer $customerModel,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->request = $request;
        $this->customerSession = $customerSession;
        $this->session = $session;
        $this->helper = $helper;
        $this->customerModel = $customerModel;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
    }

    public function execute()
    {
        $userData = null;
        // echo "<pre>";print_r($post = (array) $this->getRequest()->getPost());die;
        $httpBadRequestCode = 400;
        $response = [
            'errors' => false,
            'message' => __('Registration successful.')
        ];

        if ($this->customerModel->userExists($this->getRequest()->getPost('email'))) {
            $response = [
                'errors' => true,
                'message' => __('A user already exists with this email id.')
            ];
        } else if($this->getRequest()->getPost('email') == ''){
          $response = [
              'errors' => true,
              'message' => __('Please select your country.')
          ];
        }else {
            /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            try {
                $userData = [
                                'firstname' => $this->getRequest()->getPost('firstname'),
                                'lastname' => $this->getRequest()->getPost('lastname'),
                                'email' => $this->getRequest()->getPost('email'),
                                'password' => $this->getRequest()->getPost('password'),
                                'password_confirmation' => $this->getRequest()->getPost('password_confirmation')
                            ];
               // print_r($userData);die();
            } catch (\Exception $e) {
                return $resultRaw->setHttpResponseCode($httpBadRequestCode);
            }
            if (!$userData || $this->getRequest()->getMethod() !== 'POST' || !$this->getRequest()->isXmlHttpRequest()) {
                return $resultRaw->setHttpResponseCode($httpBadRequestCode);
            }
            try {
                $isUserRegistered = $this->customerModel->createUser($userData);

                if (!$isUserRegistered) {
                    $response = [
                        'errors' => true,
                        'message' => __('Something went wrong.')
                    ];
                } else {
                    $customer = $this->customerAccountManagement->authenticate(
                        $userData['email'],
                        $userData['password']
                );

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();
                 // if(!empty($this->session->getData('referid'))){
                    // echo $referid = $this->session->getData('referid');die;
                    $themeTable = $connection->getTableName('referral_system');
                    $themeTable2 = $connection->getTableName('user_rewards');
                    $checkReferral = "SELECT * FROM " . $themeTable . " WHERE recipient_email ='".$userData['email']."'";
                    $result = $connection->fetchAll($checkReferral);

                if(!empty($result)){
                    foreach($result as $collection){
                        if($collection['signUp_status'] == 1){
                            $referralMsg = "This user is already registered. Please refer other friend.";
                        }elseif($collection['signUp_status'] == 0){

                            $sql = "INSERT INTO " . $themeTable2 . " (customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$collection['user_id'].", 50, 'credit', 'referral_reward')";
                            $connection->query($sql);

                            $sql2 = "UPDATE " . $themeTable . " SET signUp_status =1 WHERE recipient_email ='".$userData['email']."'";
                            $connection->query($sql2);
                        }

                    }
                }

                // }
                $sql = "INSERT INTO " . $themeTable2 . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customer->getId().", 100, 'credit', 'signUp_reward')";
                $connection->query($sql);


                $mobile = $_POST['contact'];
                $city = $_POST['city'];
                $country = $_POST['country'];
                $street = $_POST['street'];
                $postcode = $_POST['postcode'];
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];


                $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
                $objectManager = $bootstrap->getObjectManager();
                $addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');

                $address = $addresss->create();
                $address->setCustomerId($customer->getId())
                        ->setTelephone($mobile)
                        ->setCity($city)
                        ->setCountryId($country)
                        ->setStreet($street)
                        ->setPostcode($postcode)
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setIsDefaultBilling('1')
                        ->setSaveInAddressBook('1');
                $address->save();

                // Sent Mail
                // die;

                $senderEmail = "hr@millipixels.com";
                $loginUrl = $this->getBaseUrl();
                $msg = '';
                $msg .= "<p>Hi</p>";
                $msg .= "<p>Thank you for registering with us. Please login with below link:-</p>";
                $msg .= "<p><a href='".$loginUrl."' target='_blank'>Click here to login.</a></p>";
                $msg .= "<p>Regards</p>";
                $msg .= "<p>Team Supertee</p>";
                $email = new \Zend_Mail();
                $email->setSubject("Welcome to Supertee");
                $email->setBodyHtml($msg);
                $email->setFrom($senderEmail);
                $email->addTo($this->getRequest()->getPost('email'));
                $email->send();

                $this->customerSession->setCustomerDataAsLoggedIn($customer);
                $this->customerSession->regenerateId();

                }
            } catch (LocalizedException $e) {
                $response = [
                    'errors' => true,
                    'message' => $e->getMessage()
                ];
            } catch (\Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => $e->getMessage()
                    // 'message' => __('Something went wrong.')
                ];
            }
        }


        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

}
}
