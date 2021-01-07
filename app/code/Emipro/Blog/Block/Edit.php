<?php
namespace Emipro\Blog\Block;
class Edit extends \Magento\Framework\View\Element\Template
{
     public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
     ) {
        parent::__construct($context, $data);
        //get collection of data 
        $data = $this->getRequest()->getParams('id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession = $objectManager->get('Magento\Customer\Model\Session');
		$postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->getCollection();
		$postCollection->addFieldToFilter('user', array('eq' => ''.$customerSession->getCustomer()->getEmail().''));
        $postCollection->addFieldToFilter('id', array('eq' => $data));
        $this->setCollection($postCollection);
    }
  
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

}
