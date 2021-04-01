<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Forms\Registration\Controller\Password;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Framework\App\ObjectManager;


/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Index extends \Magento\Framework\App\Action\Action
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
    protected $session;

    protected $connection;
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
     * @var AccountRedirect
     */
    protected $accountRedirect;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $customerRepository;

    /**
     * Encryptor.
     *
     * @var \Magento\Framework\Encryption\Encryptor
     */
    protected $encryptor;

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
        ResourceConnection $resource,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $tierPrice,
        \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository,
        \Magento\Framework\Encryption\Encryptor $encryptor
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
        $this->connection           = $resource->getConnection();
        $this->customerRepository = $customerRepository;
        $this->encryptor          = $encryptor;
    }

    public function execute(){

        $post = (array) $this->getRequest()->getPost();
        $token = $this->getRequest()->getPost('token');
        $password = $this->getRequest()->getPost('password');
        $cpassword = $this->getRequest()->getPost('confirm_password');
        if(empty($password)) {
            $response = [
                'errors' => true,
                'message' => 'Password can not be blank'
            ];
        } elseif(empty($cpassword)) {
            $response = [
                'errors' => true,
                'message' => 'Confirm Password can not be blank'
            ];
        } elseif ($password != $cpassword) {
            $response = [
                'errors' => true,
                'message' => 'Confirm Password not matching'
            ];
        } elseif (empty($token)) {
            $response = [
                'errors' => true,
                'message' => 'Invalid token'
            ];
        } elseif (!empty($token)) {
            $themeTable = $this->connection->getTableName('resetPasswordLinks');
            $checkToken = "SELECT * FROM " . $themeTable . " WHERE token_key ='$token'";
            $result = $this->connection->fetchAll($checkToken);
            if (empty($result)) {
                $response = [
                    'errors' => true,
                    'message' => 'Invalid token'
                ];  
            }
            
        }

        if (empty($response)) {

            // save password
            $emailadd = $result[0]['email_address'];
            $customer = $this->customerRepository->get($emailadd);
            $customerId = $customer->getId();
            $customer = $this->customerRepository->getById($customerId); // website also we can define
            $this->customerRepository->save($customer, $this->encryptor->getHash($password, true));
            
            // send success messgae
            $senderEmail = "hr@millipixels.com";
            $loginUrl = $this->getBaseUrl();
            $msg = '';
            $msg .= "<p>Hi</p>";
            $msg .= "<p>Your password has been changed successfully.</p>";
            $msg .= "<p>Regards</p>";
            $msg .= "<p>Team Supertee</p>";
            $email = new \Zend_Mail();
            $email->setSubject("Password changed - Supertee");
            $email->setBodyHtml($msg);
            $email->setFrom($senderEmail);
            $email->addTo($emailadd);
            $email->send();
            
            // check customer authentication
            $customer = $this->customerAccountManagement->authenticate(
                $emailadd,
                $password
            );
            $this->customerSession->setCustomerDataAsLoggedIn($customer);
            $this->customerSession->regenerateId();
            $redirectRoute = $this->getAccountRedirect()->getRedirectCookie();
            if (!$this->getScopeConfig()->getValue('customer/startup/redirect_dashboard') && $redirectRoute) {
                $response['redirectUrl'] = $this->_redirect->success($redirectRoute);
                $this->getAccountRedirect()->clearRedirectCookie();
            }
            $response = [
                'error' => false,
                'message' => 'Reset password successfully.'
            ];
        }
        
        // /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);

        }
        
    /**
     * Get account redirect.
     * For release backward compatibility.
     *
     * @deprecated
     * @return AccountRedirect
     */
    protected function getAccountRedirect()
    {
        if (!is_object($this->accountRedirect)) {
            $this->accountRedirect = ObjectManager::getInstance()->get(AccountRedirect::class);
        }
        return $this->accountRedirect;
    }
     /**
     * @deprecated
     * @return ScopeConfigInterface
     */
    protected function getScopeConfig()
    {
        if (!is_object($this->scopeConfig)) {
            $this->scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        }
        return $this->scopeConfig;
    }
    /**
     * @deprecated
     * @param ScopeConfigInterface $value
     * @return void
     */
    public function setScopeConfig($value)
    {
        $this->scopeConfig = $value;
    }

    function getBaseUrl(){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

}