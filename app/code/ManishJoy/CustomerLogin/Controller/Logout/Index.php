<?php 
namespace ManishJoy\CustomerLogin\Controller\Logout;

use Magento\Customer\Model\Session;
// use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
// use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    //const MODULE_ENABLED = 'customerlogin/general/enable';
    protected $coreRegistry;
    protected $url;
    protected $resultFactory;
    protected $messageManager;
    protected $session;
    
    public function __construct(Registry $registry,
        UrlInterface $url,
        // ManagerInterface $messageManager,
        // ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        ResultFactory $resultFactory
    )
    {
        $this->session = $customerSession;
        $this->coreRegistry = $registry;
        $this->url = $url;
        $this->resultFactory = $resultFactory;
        // $this->messageManager = $messageManager;
        // $this->_scopeConfig = $scopeConfig;
    }

public function execute()
    {
        die('he');
            // $this->messageManager->getMessages(true);
            // Adding a custom message
            // $this->messageManager->addErrorMessage(__('logout successfully.'));
            // Destroy the customer session in order to redirect him to the login page
            $this->session->destroy();
            /** @var \Magento\Framework\Controller\Result\Redirect $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $result->setUrl($this->url->getUrl('/'));
            return $result;
        } 

    }