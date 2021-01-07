<?php
namespace Emipro\Blog\Controller\Index;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Ajaxform extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
	  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	  $customerSession = $objectManager->get('Magento\Customer\Model\Session');
	  if($customerSession->isLoggedIn()) {
        $block = $resultPage->getLayout()
                ->createBlock('Emipro\Blog\Block\Ajaxform')
                ->setTemplate('Emipro_Blog::indexform.phtml')
                ->toHtml();
       }else{
       	$block = $resultPage->getLayout()
                ->createBlock('Magento\Customer\Block\Form\Login')
                ->setTemplate('Magento_Customer::form/login.phtml')
                ->toHtml();
        }
        $this->getResponse()->setBody($block);
    }
}