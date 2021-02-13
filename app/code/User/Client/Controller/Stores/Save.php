<?php
/**
 *
 * Copyright © 2015 Usercommerce. All rights reserved.
 */
namespace User\Client\Controller\Stores;
use Magento\Framework\App\ResourceConnection;
use \Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $_mediaDirectory;
    protected $_fileUploaderFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,

        \Forms\Registration\Model\Session $session,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        ResourceConnection $resource,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;

        $this->session = $session;
        $this->connection = $resource->getConnection();
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
    }
    
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
         /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $post = (array) $this->getRequest()->getPost();
        $resultRedirect->setUrl($this->_redirect->getRefererUrl()."?res=".$response);

        return $resultRedirect;
        // echo "<pre>";print_r($post);
        // die;
        $resultRedirect = $this->resultRedirectFactory->create();
        // try{
        $bannerImages = $this->getRequest()->getFiles('bannerImages');
        $galleryImages = $this->getRequest()->getFiles('galleryImages');
        $storeLogo = $this->getRequest()->getFiles('storeLogo');
        
        
        if(!empty($post['SavePublish']) && ($post['SavePublish'] == 'SavePublish')){
            $changeStatus = 'Published';
        }elseif(!empty($post['save']) && ($post['save'] == 'SaveSubmit')){
            $changeStatus = 'Draft';
        }
        $storeSetup_images = $this->connection->getTableName('storeSetup_images');
        $storeSetup_info = $this->connection->getTableName('storeSetup_info');

        $parent_id = $post['pid'];
        $getMyClientId = $post['cid'];
        $storeId = $post['sid'];
        $storeCode = $post['scode'];
            
            if(!empty($post['save']) && ($post['save'] == 'SaveSubmit')){
                $sql = "INSERT INTO ".$storeSetup_info."(client_id, customClient_id, store_id, storeCode, theme_id, isStoreActive, updatesNotification, desktopNotification, pushNotification, status) VALUES ('".$getMyClientId."', '".$parent_id."', '".$storeId."', '".$storeCode."', '".$post['getTheme']."', '".$post['updatesNotification']."', '".$post['desktopNotification']."', '".$post['pushNotification']."', '".$changeStatus."')";

                if($this->connection->query($sql)){
                    $lastInsertedId = $this->connection->lastInsertId();
                    $response['Draft_setupInfo'] = true;
                }else{
                    $response['Draft_setupInfo'] = false;
                }

            }elseif(!empty($post['SavePublish']) && ($post['SavePublish'] == 'SavePublish')){
                $sql = "UPDATE " . $storeSetup_info . " SET 
                        theme_id = '".$post['getTheme']."', 
                        updatesNotification = '".$post['updatesNotification']."', 
                        desktopNotification = '".$post['desktopNotification']."', 
                        pushNotification = '".$post['pushNotification']."', 
                        status = '".$changeStatus."', 
                        WHERE id= ".$parent_id;

                if($this->connection->query($sql)){
                    $addMessage = true;
                    $response['published_setupInfo'] = true;
                }else{
                    $response['published_setupInfo'] = false;
                }
            }

            if(count($storeLogo) > 0){
                $storesLogoName = time()."_".str_replace('+', "_", $_FILES['storeLogo']['name']);
              // foreach ($storeLogo as $storeLogoFile) {
                if (isset($storeLogoFile['tmp_name']) && strlen($storeLogoFile['tmp_name']) > 0) {
                  try {
                    $mediapath = '/storesLogo/';
                    uploadFiles($mediapath, 'storeLogo');

                    if($changeStatus == 'Published'){
                        $sql = "UPDATE " . $storeSetup_theme . " SET 
                        theme_id = '".$post['getTheme']."', 
                        storeLogo = '".$storesLogoName."', 
                        status = '".$changeStatus."' 
                        WHERE id= '".$post['themeSetup']."'";

                        $storeResponse = 'published_store';

                    }elseif($changeStatus == 'Draft'){
                        $sql = "INSERT INTO ".$storeSetup_theme."(store_id, client_id, store_setup_id, theme_id, storeLogo) VALUES ('".$storeId."', '".$getMyClientId."', '".$lastInsertedId."', '".$post['getTheme']."', '".$storesLogoName."')";
                        $storeResponse = 'draft_store';
                    }
                        if($this->connection->query($sql)){
                            // $LogoMessage = true;
                            $response[$storeResponse] = true;
                        }else{
                            $response[$storeResponse] = false;
                            
                        }
                  }catch (\Exception $e) {
                    $addError = "Error for logo";
                  }
                }
            }

            if(count($bannerImages) > 0){
              $i = 0;
              foreach ($bannerImages as $files) {
                if (isset($files['tmp_name']) && strlen($files['tmp_name']) > 0) {
                  try {
                    $mediapath = '/clientStores/'.$post['cid'];
                    // uploadFiles($mediapath, $bannerImages[$i]); 
                    if(uploadFiles($mediapath, $bannerImages[$i])){
                        $BannerName = time()."_".str_replace('+', "_", $galleryImages[$i]['name']);
                        $sql = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$BannerName."', 'gallery', '".$changeStatus."')";
                        if($this->connection->query($sql)){
                            $addMessage = true;
                        }else{
                            $addMessage = false;
                        }
                    }

                  }catch (\Exception $e) {
                    $addError = "Error for banners";
                  }
                }
                $i++;
              }
            }

            if(count($galleryImages) > 0){
              $j = 0;
                foreach ($galleryImages as $galleryfiles) {
                if (isset($galleryfiles['tmp_name']) && strlen($galleryfiles['tmp_name']) > 0) {
                  try {
                    $mediapath = '/clientStores/'.$post['cid'];
                    if(uploadFiles($mediapath, $galleryImages[$j])){
                        $imageName = time()."_".str_replace('+', "_", $galleryImages[$i]['name']);
                        $sql = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$imageName."', 'gallery', '".$changeStatus."')";
                        if($this->connection->query($sql)){
                            $addMessage = true;
                        }else{
                            $addMessage = false;
                        }
                    }
                    
                  }catch (\Exception $e) {
                    $addError = "Error for galleryfiles";
                  }
                }
                $j++;
              }
            }
        $this->resultPage = $this->resultPageFactory->create();  
        return $this->resultPage;
        
    }

    function uploadFiles($targetDirectory, $inputName){
            $target = $this->_mediaDirectory->getAbsolutePath($targetDirectory.'/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => $inputName]); //Since in this example the input controller name is 'profileAdd', it shoud be used here
            /** Allowed extension types */
            $uploader->setAllowedExtensions(['jpg', 'png']);
            /** rename file name if already exists */
            $uploader->setAllowRenameFiles(true);
            /** upload file in folder "mycustomfolder" */
            $result = $uploader->save($target);
            if ($result['file']) {
                // echo "File has been successfully uploaded";die;
                return true;
            }else{
                return false;
            }
    }
}
