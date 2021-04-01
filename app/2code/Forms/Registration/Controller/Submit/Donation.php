<?php
namespace Forms\Registration\Controller\Submit;
use Magento\Framework\Controller\ResultFactory;
// use Magento\Framework\Math\Random;

// use \Magento\Customer\Model\Session;
// use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;


class Donation extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultRedirect;
    protected $_redirect;
    protected $_url;
    protected $customer;


     public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customer,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
        \Forms\Registration\Model\Session $session,

    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);

        $this->session = $session;
        $this->customer = $customer;
        $this->_url = $url;
        $this->_redirect = $redirect; 
        $this->resultRedirect = $result;


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

            try {

                $this->session->unsetData("donation_data");
                $this->session->setData("donation_data", $post);
                
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