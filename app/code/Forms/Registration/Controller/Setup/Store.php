<?php
namespace Forms\Registration\Controller\Setup;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Store extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	private $configInterface;

	protected $_mediaDirectory;
    protected $_fileUploaderFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		ConfigInterface $configInterface,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Forms\Registration\Model\Session $session,
		\Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
		JsonFactory $resultJsonFactory
	)
	{
		$this->_pageFactory = $pageFactory;
		$this->configInterface = $configInterface;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->session = $session;

		$this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$result = $this->resultJsonFactory->create();
		
		$data = $this->getRequest()->getPostValue();

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        

		$getMyStore = $this->session->getData("getMyStore");
		$getMyClientId = $this->session->getData("getMyClientId");
		$getMyCustomId = $this->session->getData("getMyCustomId");
		// $storeId = $data['tempSID'];

		if(!empty($data['productsData']) && ($data['productsData'] == 'true')){
			$storeId = $data['tempSID'];
			if(!empty($data['firstSelectProducts'])){
				if($data['firstSelectProducts'] == 'allProducts'){
					$categoriesCollected = 'all';
					$productsCollected = 'all';
				}elseif($data['firstSelectProducts'] == 'specificProduct'){
					$categoriesCollected = $data['categoriesCollected'];
					$productsCollected = $data['productsCollected'];
				}
			}else{
				return $result->setData(['output' => false]);
			}
			$SetupStoreTable = $connection->getTableName('storeSetup_info');

			$validateStoreName = "SELECT store_id FROM " . $SetupStoreTable . " WHERE store_id = '".$storeId."'  ORDER BY id DESC";
            $response = $connection->fetchAll($validateStoreName);

            if(!empty($response)){
               return $result->setData(['output' => false]);
            }

			$sql = "INSERT INTO ".$SetupStoreTable."(client_id, customStore_id, store_id, storeCode, CategoriesSelect, productsSelected) VALUES ('".$getMyClientId."', '".$getMyCustomId."', '".$storeId."', '".$getMyStore."', '".$categoriesCollected."', '".$productsCollected."')";

	        if($connection->query($sql)){
	            // $this->session->setData("ClientPersonalData", $data);
	            $result->setData(['output' => $connection->lastInsertId()]);
	        }else{
	            $result->setData(['output' => false]);
	        }
        }elseif(!empty($data['markUpSetting']) && ($data['markUpSetting'] == 'true')){
        	$storeId = $data['tempSID'];
        	if(!empty($data['setMarkUp'])){
				if($data['setMarkUp'] == 'allProducts'){
					$singleMarkUpAmt = $data['singleMarkUpAmt'];
					// $productsCollected = 'all';
				}elseif($data['setMarkUp'] == 'specificProduct'){
					$singleMarkUpAmt = $data['singleMarkUpAmt'];
				}
			}else{
				$result->setData(['output' => false]);
			}


        	$markUpTable = $connection->getTableName('storeSetup_markup');

        	//$sql = "INSERT INTO ".$markUpTable."(store_setup_id, client_id, store_id, product_id, markup) VALUES ('".$data['']."', '".$getMyClientId."', '".$storeId."', '".$data['']."', '".$data['categoriesCollected']."', '".$data['productsCollected']."')";


	        if($connection->query($sql)){
	            // $this->session->setData("ClientPersonalData", $data);
	            $result->setData(['output' => $connection->lastInsertId()]);
	        }else{
	            $result->setData(['output' => false]);
	        }
        }elseif(!empty($data['selectTheTheme']) && ($data['selectTheTheme'] == 'true')){
        	$storeSetup_theme = $connection->getTableName('storeSetup_theme');
        	$storeId = $data['tempSID'];

        	$validateStoreName = "SELECT store_id FROM " . $storeSetup_theme . " WHERE store_id = '".$storeId."'  ORDER BY id DESC";
            $response = $connection->fetchAll($validateStoreName);

            if(!empty($response)){
               return $result->setData(['output' => false]);
            }
            
        	$this->configInterface->saveConfig('design/theme/theme_id', $data['getTheme'], 'stores', $storeId);
        	
        	$storesLogoName = time()."_".str_replace('+', "_", $_FILES['storeLogo']['name']);
        	// $storesLogoName = time()."_".$_FILES['storeLogo']['name'];
        	$target = $this->_mediaDirectory->getAbsolutePath('storesLogo/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'storeLogo']); //Since in this example the input controller name is 'profileAdd', it shoud be used here
            /** Allowed extension types */
            $uploader->setAllowedExtensions(['jpg', 'png']);
            /** rename file name if already exists */
            $uploader->setAllowRenameFiles(true);
            /** upload file in folder "mycustomfolder" */
            $fileUpload = $uploader->save($target, $storesLogoName);
            if ($fileUpload['file']) {
            	$storesLogoName = $storesLogoName;
            }else{
            	$storesLogoName = '';
            }


                
            $sql = "INSERT INTO ".$storeSetup_theme."(store_id, client_id, store_setup_id, theme_id, storeLogo) VALUES ('".$storeId."', '".$getMyClientId."', '".$data['SSID']."', '".$data['getTheme']."', '".$storesLogoName."')";

                if($connection->query($sql)){
			        $result->setData(['output' => $storesLogoName]);
		        }else{
		            $result->setData(['output' => false]);
		        }
            }elseif(!empty($data['setGeneralSetting']) && ($data['setGeneralSetting'] == 'true')){
        	$storeId = $data['tempSID'];
        	$ThemeSSID = $data['ThemeSSID'];
        	
        	$storeSetup_info = $connection->getTableName('storeSetup_info');

        	$sql = "UPDATE " . $storeSetup_info . " SET 
	                    updatesNotification = '".$data['updatesNotification']."', 
	                    stripeNotification = '".$data['stripeNotification']."', 
	                    desktopNotification = '".$data['desktopNotification']."', 
	                    pushNotification = '".$data['pushNotification']."', 
	                    WHERE id= ".$ThemeSSID;
            if($connection->query($sql)){
                    $result->setData(['output' => true]);
            }else{
                $result->setData(['output' => false]);
            }
        }
        return $result;
        }
}