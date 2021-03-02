<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ManishJoy\CustomerLogin\Controller\Ajax;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;
/**
 * Login controller
 *
 * @method \Magento\Framework\App\RequestInterface getRequest()
 * @method \Magento\Framework\App\Response\Http getResponse()
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Forget extends \Magento\Framework\App\Action\Action
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
        \Magento\Customer\Model\Session $customerSession,
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Json\Helper\Data $helper,
        \ManishJoy\CustomerLogin\Model\Customer $customerModel,
        \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Request\Http $request,
        ResourceConnection $resource,
        \Magento\Framework\Math\Random $mathRandom,
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

        $this->connection           = $resource->getConnection();
        $this->mathRandom = $mathRandom;

        
    }

    public function execute()
    {
        $userData = null;
        $httpBadRequestCode = 400;
        // $response = [
        //     'errors' => false,
        //     'message' => __('Registration successful.')
        // ];
        $post = (array) $this->getRequest()->getPost();
        $themeTable = $this->connection->getTableName('resetPasswordLinks');
        try {
        if ($this->customerModel->userExists($this->getRequest()->getPost('email'))) {
            $email = $this->getRequest()->getPost('email');

            $randomString =  $this->getRandomString(12);
            $resetPasswordLink = $this->getBaseUrl()."?reset=".$randomString;
            $senderEmail = "hr@millipixels.com";
            // $password_request_time = date('Y-m-d H:i:s');
            $sql = "INSERT INTO " . $themeTable . "(email_address, token_key) VALUES ('".$email."', '".$randomString."')";
            $this->connection->query($sql);
                // die;
                $msg = '';
                $msg .= "<p>Hi</p>";
                $msg .= "<p>You have asked to reset your password. Please click below link to reset your password.</p>";
                $msg .= "<p><a href='".$resetPasswordLink."' target='_blank'>Click here to reset password.</a></p>";
                $msg .= "<p>Regards</p>";
                $msg .= "<p>Team Supertee</p>";
                // echo $msg;die;
                $email = new \Zend_Mail();
                $email->setSubject("Reset password");
                $email->setBodyHtml($msg);
                // $email->setBodyText("You have been referred by your friend ".$referralUserName." to join Supertee.");
                // $email->setBodyText("Here you can customise the products online and get it delivered at your door step.");
                // $email->setBodyText("Please click the below given link to join the application.");
                // $email->setBodyText("hope you are good");
                $email->setFrom($senderEmail);
                $email->addTo($post['email']);
                $email->send();

            $response = [
                'errors' => false,
                'message' => __('Link has been sent to your registered email address.')
            ];
        } else {

            $response = [
                'errors' => true,
                'message' => __('Sorry! this email address is not available with us.')
            ];
        }
    }catch (Exception $e) {
                $response = [
                'errors' => true,
                'message' => __('Something went wrong. Please try again.')
            ];
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

    public function getRandomString($length,  $chars = null)
    {
        return $this->mathRandom->getRandomString($length, $chars);
    }
}