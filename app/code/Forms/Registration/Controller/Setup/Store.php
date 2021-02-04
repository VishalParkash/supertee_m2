<?php
namespace Forms\Registration\Controller\Setup;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Store extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	private $configInterface;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		ConfigInterface $configInterface,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Forms\Registration\Model\Session $session,
		JsonFactory $resultJsonFactory
	)
	{
		$this->_pageFactory = $pageFactory;
		$this->configInterface = $configInterface;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->session = $session;
		return parent::__construct($context);
	}

	public function execute()
	{
		$result = $this->resultJsonFactory->create();
		$this->configInterface->saveConfig('design/theme/theme_id', 5, 'stores', 15);
		$data = $this->getRequest()->getPostValue();

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        

		$getMyStore = $this->session->getData("getMyStore");
		$getMyClientId = $this->session->getData("getMyClientId");
		$getMyCustomId = $this->session->getData("getMyCustomId");
		$storeId = $data['tempSID'];

		if(!empty($data['productsData']) && ($data['productsData'] == 'true')){
			$SetupStoreTable = $connection->getTableName('storeSetup_info');

			$sql = "INSERT INTO ".$SetupStoreTable."(client_id, customClient_id, store_id, storeCode, CategoriesSelect, productsSelected) VALUES ('".$getMyClientId."', '".$getMyCustomId."', '".$storeId."', '".$getMyStore."', '".$data['categoriesCollected']."', '".$data['productsCollected']."')";

        if($connection->query($sql)){
            // $this->session->setData("ClientPersonalData", $data);
            $result->setData(['output' => $connection->lastInsertId()]);
        }else{
            $result->setData(['output' => false]);
        }
	        return $result;
        }

        if(!empty($data['markUpSetting']) && ($data['markUpSetting'] == 'true')){
        	$markUpTable = $connection->getTableName('storeSetup_info');

        	//$sql = "INSERT INTO ".$SetupStoreTable."(store_setup_id, client_id, store_id, product_id, markup) VALUES ('".$data['']."', '".$getMyClientId."', '".$storeId."', '".$data['']."', '".$data['categoriesCollected']."', '".$data['productsCollected']."')";


        if($connection->query($sql)){
            // $this->session->setData("ClientPersonalData", $data);
            $result->setData(['output' => $connection->lastInsertId()]);
        }else{
            $result->setData(['output' => false]);
        }
	        return $result;
        }


		// return $this->_pageFactory->create();
	}
}