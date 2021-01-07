<?php
namespace Forms\Registration\Controller\Zeke;
use Magento\Framework\Controller\ResultFactory;
use Forms\Registration\Model\DataExampleFactory;

class Customize extends \Magento\Framework\App\Action\Action
{
	// protected $_pageFactory;
	// protected $_dataExample;
 //    protected $resultRedirect;


    //  public function __construct(\Magento\Framework\App\Action\Context $context,
    //     \Forms\Registration\Model\DataExampleFactory  $dataExample,
    // \Magento\Framework\Controller\ResultFactory $result){
    //     parent::__construct($context);
    //     $this->_dataExample = $dataExample;
    //     $this->resultRedirect = $result;


    // }


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		return $this->_pageFactory->create();
	}
}