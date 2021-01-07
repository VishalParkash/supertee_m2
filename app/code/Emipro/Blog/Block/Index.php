<?php
namespace Emipro\Blog\Block;
class Index extends \Magento\Framework\View\Element\Template
{
     protected $_gridFactory; 
     public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
     ) {
        parent::__construct($context, $data);
        //get collection of data 
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$postCollection = $objectManager->create('Emipro\Blog\Model\Blog')->getCollection();
        $postCollection->addFieldToFilter('status', array('eq' => 1));
		$postCollection->setOrder('id', 'DESC');
        $this->setCollection($postCollection);
        //$this->pageConfig->getTitle()->set(__('Emipro Blog'));
    }
  
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getCollection()) {
            // create pager block for collection 
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'emipro.blog.record.pager'
            )->setCollection(
                $this->getCollection() // assign collection to pager
            );
            $this->setChild('pager', $pager);// set pager block in layout
        }
        return $this;
    }
  
    /**
     * @return string
     */
    // method for get pager html
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
