<?php
namespace Emipro\Blog\Controller\Index;

class Post extends \Magento\Framework\App\Action\Action
{
    protected $_timezoneInterface;
    protected $_url;
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\UrlInterface $url, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface) {
        parent::__construct($context);
        $this->_url = $url;
        $this->_timezoneInterface = $timezoneInterface;
    }
  public function execute()
  {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $objecturl = $objectManager->create('Magento\Framework\Url\Helper\Data');
      $customerSession = $objectManager->get('Magento\Customer\Model\Session');
      if($customerSession->isLoggedIn()) {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams();
        if($data){
        $content = $this->_objectManager->create('Emipro\Blog\Model\Blog');
        $content->setData($data);
        $content->setUser(''.$customerSession->getCustomer()->getEmail().'');
        $content->setStatus(1);
        $today = $this->_timezoneInterface->date()->format('y-m-d');
        $content->setCreateat($today);
        $content->save();
        $this->messageManager->addSuccess(__("Post Successfully Submited."));
        return $resultRedirect->setPath('blog/customer');
        }else{
        $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
        return $resultRedirect->setPath('blog/customer');
        }
      }else{
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->messageManager->addError(__("Post Is Not Submited. Because Login Reuqire. "));
        $customerBeforeAuthUrl = $this->_url->getUrl('customer/account/login', array('referer' => $objecturl->getEncodedUrl($this->_url->getUrl('blog/customer'))));
        return $resultRedirect->setPath($customerBeforeAuthUrl);
      }
  }
}