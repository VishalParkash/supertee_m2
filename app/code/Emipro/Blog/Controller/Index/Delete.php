<?php
namespace Emipro\Blog\Controller\Index;

class Delete extends \Magento\Framework\App\Action\Action
{
   public function __construct(\Magento\Framework\App\Action\Context $context) {
        parent::__construct($context);
    }
  public function execute()
  {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $customerSession = $objectManager->get('Magento\Customer\Model\Session');
      $resultRedirect = $this->resultRedirectFactory->create();
      if($customerSession->isLoggedIn()) {
        $data = $this->getRequest()->getParams('id');
        $postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->getCollection();
        $postCollection->addFieldToFilter('id', array('eq' => $data));
        $postuser = $postCollection->getFirstItem()->getUser();
        $loginuser = $customerSession->getCustomer()->getEmail();
        if($postuser == $loginuser){
          if($data){
          $content = $this->_objectManager->create('Emipro\Blog\Model\Blog');
          $content->load($data);
          $content->delete();
          $this->messageManager->addSuccess(__("Post Successfully Delete."));
          return $resultRedirect->setPath('blog/customer');
          }else{
          $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
          return $resultRedirect->setPath('blog/customer');
          }
        }else{
        $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
        return $resultRedirect->setPath('blog/customer');
        }
      }else{
        $this->messageManager->addError(__("Somthing Wrong. Please Try Again."));
        return $resultRedirect->setPath('blog/customer');
      }
    }
}