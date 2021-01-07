<?php
namespace Forms\Registration\Controller\Zeke;
use Magento\Framework\Controller\ResultFactory;
// use Forms\Registration\Model\DataExampleFactory;

class Endpoint extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $_dataExample;
    protected $resultRedirect;


     public function __construct(\Magento\Framework\App\Action\Context $context,
        \Forms\Registration\Model\DataExampleFactory  $dataExample,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->_dataExample = $dataExample;
        $this->resultRedirect = $result;


    }

	// public function __construct(
	// 	\Magento\Framework\App\Action\Context $context,
	// 	\Magento\Framework\View\Result\PageFactory $pageFactory)
	// {
	// 	$this->_pageFactory = $pageFactory;
	// 	return parent::__construct($context);
	// }

	public function execute()
	{

		die('helo');
		$post = '{
			    "productid":1,
			    "quantity":1,
			    "selectedattributes":
				[{"color":"White"}]),
			    "zakekeprice": 10.5,
			    "zakekepercentageprice": 10,
			}';
		$jsonDecode = json_decode($post, TRUE);
		echo "<pre>";print_r($jsonDecode);die;




		if (!empty($post)) {
            // Retrieve your form data
            $email   = $post['vendorEmail'];
            $password    = $post['vendorPassword'];

            $model = $this->_dataExample->create();
            $model->addData([
			"email" => $email,
			"password" => $password,
			
			]);

            $saveData = $model->save();

	        // if($saveData){
	        //     $this->messageManager->addSuccess( __('Insert Record Successfully !') );
	        // }
            // Redirect to your form page (or anywhere you want...)
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('magento/listproducts/zeke/zeke/');

            return $resultRedirect;
        }

		// return $this->_pageFactory->create();
	}
}