<?php
/**
 *
 * Copyright © 2015 Createcommerce. All rights reserved.
 */
namespace Create\Store\Controller\Personal;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
use Magento\Framework\Controller\Result\JsonFactory;
// use Magento\Framework\Exception\InputException;

class Information extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $resultJsonFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Forms\Registration\Model\Session $session,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {   
        $result = $this->resultJsonFactory->create();
        $data = $this->getRequest()->getPostValue();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $themeTable = $connection->getTableName('store_clientpersonalinfo');


        if($data['password'] != $data['confirmPassword']){
            $error = "Password and Confirm Password should have same values.";
            return $result->setData(['output' => "passwordError"]);
        }
            
        $sql = "INSERT INTO " . $themeTable . " (name, email, phone_number, address, zipcode, location, country, password) VALUES ('".$data['name']."', '".$data['email']."', '".$data['phone_number']."', '".$data['address']."', '".$data['zipcode']."', '".$data['location']."', '".$data['country']."', '".$data['password']."')";
        if($connection->query($sql)){
            $this->session->setData("ClientPersonalData", $data);
            $result->setData(['output' => $connection->lastInsertId()]);
        }else{
            $result->setData(['output' => false]);
        }
        return $result;
        
    }
    // public function checkPasswordConfirmation($password, $confirmation) {
    //     if ($password != $confirmation) {
    //         throw new InputException ( __ ( 'Please make sure your passwords match.' ) );
    //     }
    // }
}
