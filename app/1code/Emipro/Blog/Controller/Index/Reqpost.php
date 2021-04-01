<?php
namespace Emipro\Blog\Controller\Index;

class Reqpost   extends \Magento\Framework\App\Action\Action
{
  protected $_timezoneInterface;
  public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
  }
  public function execute()
  {
      $data = $this->getRequest()->getParams();
      $string = $data['searchCriteria']['filter_groups'][0]['filters'][0]['value'];
      if($string != ''){
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $resultRedirect = $this->resultRedirectFactory->create();
      $postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->getCollection();
      $postCollection->addFieldToFilter('status', array('eq' => 1));
      $postCollection->addFieldToFilter(
            array('title', 'content', 'name'),
            array(
                array('like' => "%$string%"),
                array('like' => "%$string%"),
                array('like' => "%$string%")
              )
          );
      $postCollection->setOrder('id', 'DESC');
      $result = $this->resultJsonFactory->create();
      return $result->setData($postCollection);
      }else{
        $result = $this->resultJsonFactory->create();
        return $result->setData(array('totalRecords'=> 0,'items'=>array()));
      }
      
  }
}