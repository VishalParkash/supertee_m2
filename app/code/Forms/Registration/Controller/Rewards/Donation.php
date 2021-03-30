<?php
namespace Forms\Registration\Controller\Rewards;
use Magento\Framework\Controller\Result\JsonFactory;

class Donation extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $resultJsonFactory;
    protected $CollectionFactory;
    protected $productCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Forms\Registration\Model\Session $session,

        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,

        // \Magento\Eav\Model\Entity\Collection\AbstractCollection $productCollectionFactory=null,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $CollectionFactory,
        // \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $attributeSetCollection,
        // \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection ,

        JsonFactory $resultJsonFactory,

        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->session = $session;
        $this->_productloader = $_productloader;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->CollectionFactory = $CollectionFactory;

        return parent::__construct($context);
    }

    public function execute(){

    	$result = $this->resultJsonFactory->create();
        $resultPage = $this->_pageFactory->create();

        try {
                $post = (array) $this->getRequest()->getPost();
                if($this->session->setData("donation_data", $post)){
                    $result->setData(['output' => $post]);    
                }else{
                    $result->setData(['output' => false]);
                }
                
                
                
                } catch (Exception $e) {
                    $result->setData(['output' => false]);
                    \Zend_Debug::dump($e->getMessage());
                }
                return $result;
        
    }
}