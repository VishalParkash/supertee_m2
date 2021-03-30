<?php
namespace Emipro\Blog\Controller\Customer;

class Index extends \Magento\Framework\App\Action\Action
{
   /**
  * Index Action*
  * @return void
  */
  public function execute()
  {
  	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $objecturl = $objectManager->create('Magento\Framework\Url\Helper\Data');
    $customerSession = $objectManager->get('Magento\Customer\Model\Session');
    if($customerSession->isLoggedIn()) {
	     $this->_view->loadLayout();
       $this->_view->renderLayout();
  	}else{
       $resultRedirect = $this->resultRedirectFactory->create();
       $customerBeforeAuthUrl = $this->_url->getUrl('customer/account/login', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl('blog/customer'))));
        return $resultRedirect->setPath($customerBeforeAuthUrl);
      }
  }
}