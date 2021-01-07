<?php
namespace Emipro\Blog\Controller\Index;

class Ajaxpost   extends \Magento\Framework\App\Action\Action
{
  protected $_timezoneInterface;
  public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
  }
  public function execute()
  {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $resultRedirect = $this->resultRedirectFactory->create();
      $postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->getCollection();
      $postCollection->addFieldToFilter('status', array('eq' => 1));
      $postCollection->setOrder('id', 'DESC');
      $postCollection->setCurPage(1) 
                      ->setPageSize(10);
      $result = $this->resultJsonFactory->create();
      return $result->setData($postCollection);
  }
}