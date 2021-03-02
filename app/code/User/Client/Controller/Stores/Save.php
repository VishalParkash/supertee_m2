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
    protected $_coreRegistry = null;

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

        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $_coreRegistry
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

        $this->_coreRegistry = $_coreRegistry;
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
        
        // $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        // return $resultRedirect;
        // // echo "<pre>";print_r($post);
        // die;
        // $resultRedirect = $this->resultRedirectFactory->create();
        // try{

        echo "<pre>";print_r($this->getRequest()->getFiles('customiserImages'));die;
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
        $storeSetup_miscInfo = $this->connection->getTableName('storeSetup_miscInfo');
        $storeSetup_theme = $this->connection->getTableName('storeSetup_theme');
        // echo "<pre>";print_r($post);die;
        $parent_id = $post['pid'];
        $getMyClientId = $post['cid'];
        $storeId = $post['sid'];
        $storeCode = $post['scode'];

        if(!empty($post['updatesNotification'])){
            $post['updatesNotification'] = $post['updatesNotification'];
        }else{
            $post['updatesNotification'] = '';
        }

        if(!empty($post['desktopNotification'])){
            $post['desktopNotification'] = $post['desktopNotification'];
        }else{
            $post['desktopNotification'] = '';
        }

        if(!empty($post['pushNotification'])){
            $post['pushNotification'] = $post['pushNotification'];
        }else{
            $post['pushNotification'] = '';
        }

        if(!empty($post['getTheme'])){
            $getTheme = $post['getTheme'];
        }else{
            $getTheme = '';
        }

        $aboutUsText = $post['aboutUsText'];
        $facebook_link = $post['facebook_link'];
        $instagram_link = $post['instagram_link'];
        $twittter_link = $post['twittter_link'];

        if(count($storeLogo) > 0){
                $storesLogoName = time()."_".str_replace('+', "_", $_FILES['storeLogo']['name']);
              // foreach ($storeLogo as $storeLogoFile) {
                if (isset($storeLogo['tmp_name']) && strlen($storeLogo['tmp_name']) > 0) {
                  try {
                    $mediapath = '/storesLogo/';
                        $this->uploadFiles($mediapath, 'storeLogo', $storesLogoName);
                    }catch (\Exception $e) {
                        $addError = "Error for logo";
                    }
                }
            }

            if(empty($storesLogoName)){
                $storesLogoName = 'Not Selected';
            }

        if(!empty($post['SavePublish']) && ($post['SavePublish'] == 'SavePublish')){
            $response = "Published";
            /*Delete all records from the store related tables of published*/

            $delete_storeSetup_info = "DELETE  FROM ".$storeSetup_info." WHERE store_id='".$storeId."'"; 
            $this->connection->query($delete_storeSetup_info);

            $delete_storeSetup_miscInfo = "DELETE  FROM ".$storeSetup_miscInfo." WHERE store_id='".$storeId."'";         
            $this->connection->query($delete_storeSetup_miscInfo);

            $delete_storeSetup_images = "DELETE  FROM ".$storeSetup_images." WHERE store_id='".$storeId."'"; 
            $this->connection->query($delete_storeSetup_images);        

            $delete_storeSetup_theme = "DELETE  FROM ".$storeSetup_theme." WHERE store_id='".$storeId."'"; 
            $this->connection->query($delete_storeSetup_miscInfo);
            /*Delete all records from the store related tables of published*/

            /*Publish the records*/

            

            //$insert_storeSetup_images = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$BannerName."', 'gallery', '".$changeStatus."')";

            /*Publish the records*/

            // if(!empty($response)){
            //     foreach($response as $miscInfo){
            //         $miscInfoId = $miscInfoId['id'];
            //     }
            // }else{
            //     $sql = "INSERT INTO ".$storeSetup_miscInfo."(store_id, client_id, aboutUsText, facebook_link, instagram_link, twittter_link,) VALUES ('".$storeId."', '".$getMyClientId."', '".$aboutUsText."', '".$facebook_link."', '".$instagram_link."', '".$twittter_link."', '".$changeStatus."')";
            // }

        }elseif(!empty($post['save']) && ($post['save'] == 'SaveSubmit')){
            $response = "Drafted";
            /*Delete all draft records from the store related tables*/
            $delete_storeSetup_miscInfo = "DELETE FROM ".$storeSetup_miscInfo." WHERE store_id='".$storeId."' AND status = 'Draft'";         
            $this->connection->query($delete_storeSetup_miscInfo);

            if(count($bannerImages) > 0){
                $delete_storeSetup_images = "DELETE FROM ".$storeSetup_images." WHERE store_id='".$storeId."' AND status = 'Draft'";    
            }

            
            $this->connection->query($delete_storeSetup_images);

            $delete_storeSetup_info = "DELETE FROM ".$storeSetup_info." WHERE store_id='".$storeId."' AND status = 'Draft'";
            $this->connection->query($delete_storeSetup_info);

            $delete_storeSetup_theme = "DELETE FROM ".$storeSetup_theme." WHERE store_id='".$storeId."' AND status = 'Draft'";
            $this->connection->query($delete_storeSetup_miscInfo);
            /*Delete all records from the store related tables of published*/

        }else{
            $response = "Error

            ";
        }

        $insert_storeSetup_info = "INSERT INTO ".$storeSetup_info."(client_id, customStore_id, store_id, storeCode, theme_id, updatesNotification, desktopNotification, pushNotification, status) VALUES ('".$getMyClientId."', '".$parent_id."', '".$storeId."', '".$storeCode."', '".$getTheme."', '".$post['updatesNotification']."', '".$post['desktopNotification']."', '".$post['pushNotification']."', '".$changeStatus."')";
                $this->connection->query($insert_storeSetup_info);
                $lastInsertedId = $this->connection->lastInsertId();
            
            $insert_storeSetup_miscInfo = "INSERT INTO ".$storeSetup_miscInfo."(store_id, client_id, aboutUsText, facebook_link, instagram_link, twittter_link, status) VALUES ('".$storeId."', '".$getMyClientId."', '".$aboutUsText."', '".$facebook_link."', '".$instagram_link."', '".$twittter_link."', '".$changeStatus."')";
                $this->connection->query($insert_storeSetup_miscInfo);

            $insert_storeSetup_theme = "INSERT INTO ".$storeSetup_theme."(store_id, client_id, store_setup_id, theme_id, storeLogo, status) VALUES ('".$storeId."', '".$getMyClientId."', '".$lastInsertedId."', '".$getTheme."', '".$storesLogoName."', '".$changeStatus."')";
            $this->connection->query($insert_storeSetup_theme);


            $this->_coreRegistry->register('storeProfileResponse', $response);
            // $resultPage = $this->resultPageFactory->create();  
            // $block = $resultPage->getLayout()->getBlock('client_store_profile');
            // echo "block<pre>";print_r($block);
            // echo "response<pre>";print_r($response);
            // $block->setData('storeProfileResponse', $response);

            if(count($bannerImages) > 0){
                // echo "<pre>";print_r($bannerImages);die;
              $i = 0;
              foreach ($bannerImages as $files) {
                if (isset($files['tmp_name']) && strlen($files['tmp_name']) > 0) {
                  try {
                    $mediapath = '/clientStores/'.$post['cid'];
                    $BannerName = time()."_".str_replace('+', "_", $bannerImages[$i]['name']);
                    // uploadFiles($mediapath, $bannerImages[$i]); 
                    if($this->uploadFiles($mediapath, $bannerImages[$i], $BannerName) ){
                        
                        $sql = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$BannerName."', 'banner', '".$changeStatus."')";
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
                    $imageName = time()."_".str_replace('+', "_", $galleryImages[$i]['name']);
                    if($this->uploadFiles($mediapath, $galleryImages[$j], $imageName )){
                        
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
            
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;





        // die;
            
        //     if(!empty($post['save']) && ($post['save'] == 'SaveSubmit')){
        //         $sql = "INSERT INTO ".$storeSetup_info."(client_id, customStore_id, store_id, storeCode, theme_id, isStoreActive, updatesNotification, desktopNotification, pushNotification, status) VALUES ('".$getMyClientId."', '".$parent_id."', '".$storeId."', '".$storeCode."', '".$post['getTheme']."', '".$post['updatesNotification']."', '".$post['desktopNotification']."', '".$post['pushNotification']."', '".$changeStatus."')";

        //         if($this->connection->query($sql)){
        //             $lastInsertedId = $this->connection->lastInsertId();
        //             $response['Draft_setupInfo'] = true;
        //         }else{
        //             $response['Draft_setupInfo'] = false;
        //         }

        //     }elseif(!empty($post['SavePublish']) && ($post['SavePublish'] == 'SavePublish')){
        //         $sql = "UPDATE " . $storeSetup_info . " SET 
        //                 theme_id = '".$post['getTheme']."', 
        //                 updatesNotification = '".$post['updatesNotification']."', 
        //                 desktopNotification = '".$post['desktopNotification']."', 
        //                 pushNotification = '".$post['pushNotification']."', 
        //                 status = '".$changeStatus."', 
        //                 WHERE id= ".$parent_id;

        //         if($this->connection->query($sql)){
        //             $addMessage = true;
        //             $response['published_setupInfo'] = true;
        //         }else{
        //             $response['published_setupInfo'] = false;
        //         }
        //     }

            

        //     if(count($bannerImages) > 0){
        //       $i = 0;
        //       foreach ($bannerImages as $files) {
        //         if (isset($files['tmp_name']) && strlen($files['tmp_name']) > 0) {
        //           try {
        //             $mediapath = '/clientStores/'.$post['cid'];
        //             // uploadFiles($mediapath, $bannerImages[$i]); 
        //             if(uploadFiles($mediapath, $bannerImages[$i])){
        //                 $BannerName = time()."_".str_replace('+', "_", $galleryImages[$i]['name']);
        //                 $sql = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$BannerName."', 'gallery', '".$changeStatus."')";
        //                 if($this->connection->query($sql)){
        //                     $addMessage = true;
        //                 }else{
        //                     $addMessage = false;
        //                 }
        //             }

        //           }catch (\Exception $e) {
        //             $addError = "Error for banners";
        //           }
        //         }
        //         $i++;
        //       }
        //     }

        //     if(count($galleryImages) > 0){
        //       $j = 0;
        //         foreach ($galleryImages as $galleryfiles) {
        //         if (isset($galleryfiles['tmp_name']) && strlen($galleryfiles['tmp_name']) > 0) {
        //           try {
        //             $mediapath = '/clientStores/'.$post['cid'];
        //             if(uploadFiles($mediapath, $galleryImages[$j])){
        //                 $imageName = time()."_".str_replace('+', "_", $galleryImages[$i]['name']);
        //                 $sql = "INSERT INTO ".$storeSetup_images."(store_id, image, imageType, status) VALUES ('".$storeId."', '".$imageName."', 'gallery', '".$changeStatus."')";
        //                 if($this->connection->query($sql)){
        //                     $addMessage = true;
        //                 }else{
        //                     $addMessage = false;
        //                 }
        //             }
                    
        //           }catch (\Exception $e) {
        //             $addError = "Error for galleryfiles";
        //           }
        //         }
        //         $j++;
        //       }
        //     }
        $this->resultPage = $this->resultPageFactory->create();  
        return $this->resultPage;
        
    }

    public function uploadFiles($targetDirectory, $inputName, $_FILEname){

        /*for later use*/
        // if(isset($_FILES['uploaded_file'])) {
        //     $maxsize = 2097152;
        //     if(($_FILES['uploaded_file']['size'] >= $maxsize)) {
        //         exit('File too large. File must be less than 2 megabytes.');
        //     }
        // }


        $fileName = time()."_".str_replace('+', "_", $_FILEname);
            $target = $this->_mediaDirectory->getAbsolutePath($targetDirectory.'/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => $inputName]); //Since in this example the input controller name is 'profileAdd', it shoud be used here
            /** Allowed extension types */
            $uploader->setAllowedExtensions(['jpg', 'png']);
            /** rename file name if already exists */
            $uploader->setAllowRenameFiles(true);
            /** upload file in folder "mycustomfolder" */
            $result = $uploader->save($target, $_FILEname);
            if ($result['file']) {
                // echo "File has been successfully uploaded";die;
                return true;
            }else{
                return false;
            }
    }
}
