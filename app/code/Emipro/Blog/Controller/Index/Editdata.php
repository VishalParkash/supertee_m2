<?php
namespace Emipro\Blog\Controller\Index;

class Editdata   extends \Magento\Framework\App\Action\Action
{
  protected $_timezoneInterface;
  public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface) {
        parent::__construct($context);
        $this->_timezoneInterface = $timezoneInterface;
  }
  public function execute()
  {
      $data = $this->getRequest()->getParams();
      if($data){
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $customerSession = $objectManager->get('Magento\Customer\Model\Session');
      $resultRedirect = $this->resultRedirectFactory->create();
      if($customerSession->isLoggedIn()) {
        $postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->load($data['id']);
        $postuser = $postCollection->getUser();
        $loginuser = $customerSession->getCustomer()->getEmail();
        if($postuser == $loginuser){
          $postCollection->setData($data);
          $today = $this->_timezoneInterface->date()->format('y-m-d');
          $postCollection->setCreateat($today);
          $postCollection->save();
          $this->messageManager->addSuccess(__("Post Successfully Edit."));
          return $resultRedirect->setPath('blog/index/edit/id/'.$data['id']);
        }else{
        $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
        return $resultRedirect->setPath('blog/index/edit/id/'.$data['id']);
        }
      }else{
        $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
        return $resultRedirect->setPath('blog/index/edit/id/'.$data['id']);
      }
    }else{
      $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
      return $resultRedirect->setPath('blog/customer');
    }
  }
}