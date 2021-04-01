<?php
namespace Emipro\Blog\Controller\Index;

class Edit extends \Magento\Framework\App\Action\Action
{
	protected $_url;
	public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\UrlInterface $url) {
	    parent::__construct($context);
	    $this->_url = $url;
	}
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
	    $this->messageManager->addNotice(__("Login Reuqire For Edit Post. So Please <i class='fa fa-lock'></i> Login Now And Edit Your Post."));
	    $customerBeforeAuthUrl = $this->_url->getUrl('customer/account/login', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl('blog/customer'))));
        return $resultRedirect->setPath($customerBeforeAuthUrl);
  	  }
  }
}