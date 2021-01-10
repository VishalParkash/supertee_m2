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
        } else {
            /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            try {
                $userData = [
                                    'firstname' => $this->getRequest()->getPost('firstname'),
                                    'lastname' => $this->getRequest()->getPost('firstname'),
                                    'email' => $this->getRequest()->getPost('email'),
                                    'telephone'=> $this->getRequest()->getPost('contact'),
                                    'password' => $this->getRequest()->getPost('password'),
                                    'password_confirmation' => $this->getRequest()->getPost('password_confirmation')
                                ];
              //  print_r($userData);die();
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

                    // echo "<pre>";echo $customer->getId();die;
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();
            //     $select = $connection->select()
            //         ->from(
            //             ['ur' => 'user_rewards'],
            //             ['*'])
            //             ->where(
            //     "ur.customer_id = ".$customer->getId()
            // );
            
            //     $data = $connection->fetchAll($select);

            //     if(empty($data)){
                // $this->session->setData("donation_data", $post);
                // echo "<pre>";print_r($this->request->getParams());die;
                // echo "<pre>";print_r($this->session->getData('referid'));die;

                if(!empty($this->session->getData('referid'))){
                    $referid = $this->session->getData('referid');
// die('in here');
                    $themeTable = $connection->getTableName('referral_system');
                    $checkReferral = "SELECT * FROM " . $themeTable . " WHERE tokenCode ='$referid'";
                    $result = $connection->fetchAll($checkReferral);

                if(!empty($result)){
                    foreach($result as $collection){
                        if($collection['signUp_status'] == 1){
                            $referralMsg = "This user is already registered. Please refer other friend.";
                        }elseif($collection['signUp_status'] == 0){

                            $themeTable2 = $connection->getTableName('user_rewards');
                            $sql = "INSERT INTO " . $themeTable2 . " (customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$collection['user_id'].", 50, 'credit', 'referral_reward')";
                            $connection->query($sql);

                            $sql2 = "UPDATE " . $themeTable . " SET signUp_status =1 WHERE tokenCode='$referid'";
                            $connection->query($sql2);
                        }

                        //  $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                        // $resultRedirect->setPath('/magento');
                        // return $resultRedirect;
                    }
                }

                }
                $themeTable = $connection->getTableName('user_rewards');
                $sql = "INSERT INTO " . $themeTable . "(customer_id, reward_points, reward_type, rewards_points_id) VALUES (".$customer->getId().", 100, 'credit', 'signUp_reward')";
                $connection->query($sql);
                // }

                // echo "<pre>";print_r($data);die;


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
}