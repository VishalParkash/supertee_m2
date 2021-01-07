<?php
namespace Forms\Registration\Controller\Edit;
use Magento\Framework\Controller\ResultFactory;
use Forms\Registration\Model\DataExampleFactory;

use Magento\Framework\App\Filesystem\DirectoryList;


class Edit extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_dataExample;
    protected $resultRedirect;
    protected $_redirect;
    protected $_url;


     public function __construct(\Magento\Framework\App\Action\Context $context,
        \Forms\Registration\Model\DataExampleFactory  $dataExample,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $redirect,
    \Magento\Framework\Controller\ResultFactory $result){
        parent::__construct($context);
        $this->_dataExample = $dataExample;
        $this->_filesystem = $fileSystem;
        $this->directory_list = $directory_list; 
        $this->_url = $url;
         $this->_redirect = $redirect; 
        $this->resultRedirect = $result;


    }


    // public function __construct(
    //  \Magento\Framework\App\Action\Context $context,
    //  \Magento\Framework\View\Result\PageFactory $pageFactory)
    // {
    //  $this->_pageFactory = $pageFactory;
    //  return parent::__construct($context);
    // }

    /** @return string */
function getMediaBaseUrl() {
/** @var \Magento\Framework\ObjectManagerInterface $om */
$om = \Magento\Framework\App\ObjectManager::getInstance();
/** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
$storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
/** @var \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store $currentStore */
$currentStore = $storeManager->getStore();
return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
}

    public function execute()
    {
      //  print_r('haha');die();
        $post = (array) $this->getRequest()->getPost();

        // echo "<pre>";print_r($post);die;
        if (!empty($post)) {
            print_r($post);die();
            // Retrieve your form data
            // $email   = $post['vendorEmail'];
            // $password    = $post['vendorPassword'];
            // $vendorName   = $post['vendorName'];
            // $vendorPhoneNumber    = $post['vendorPhoneNumber'];
            // $vendorAddress    = $post['vendorAddress'];
            // $vendorZipCode   = $post['vendorZipCode'];
            // $vendorTown    = $post['vendorTown'];
            // $vendorCountry   = $post['vendorCountry'];
            // $vendorCity    = $post['vendorCity'];
            $textColorSelector   = $post['textColorSelector'];
            $vendorStoreName    = $post['vendorStoreName'];
            $firstTagline    = $post['firstTagline'];
            $textFontSelector    = $post['textFontSelector'];
            $canvasFile    = $post['canvasFile'];


            if ($_FILES['Vendorlogo']['name']) {
            try {

                list ( $width, $height ) = getimagesize ( $_FILES ["Vendorlogo"] ['tmp_name'] );
                // if($height < 110 || $width < 150){
                //     $this->messageManager->addError ( __ ( 'Minimum Upload image size for Logo is 150 X 110' ) );
                //     $this->_redirect ( '*/*/profile' );
                //     return;
                // }

                // init uploader model.
                $uploader = $this->_objectManager->create(
                    'Magento\MediaStorage\Model\File\Uploader',
                    ['fileId' => 'Vendorlogo']
                );

                $temp = explode(".", $_FILES["Vendorlogo"]["name"]);
                $Vendorlogo = round(microtime(true)) . '.' . end($temp);

                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                // get media directory
                $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $directory = $this->directory_list->getPath('pub');
                // save the image to media directory
                // file_put_contents($directory.'/media/Vendorlogo/'."_".$Vendorlogo, $_FILES["Vendorlogo"]["tmp_name"]);
                move_uploaded_file($_FILES["Vendorlogo"]["tmp_name"], $directory.'/media/VendorLogo/'."_".$Vendorlogo);
                // $result = $uploader->save($mediaDirectory->getAbsolutePath('/Vendorlogo/'));
            } catch (Exception $e) {
                \Zend_Debug::dump($e->getMessage());
            }
        }


            list($type, $canvasFile) = explode(';', $canvasFile);
            list(, $canvasFile)      = explode(',', $canvasFile);
            // $canvasFile = base64_decode($canvasFile);

            $om = \Magento\Framework\App\ObjectManager::getInstance();
            /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
            $storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
            /** @var \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store $currentStore */
            $currentStore = $storeManager->getStore();
            $storeManagerUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $directory = $this->directory_list->getPath('pub');
            $VendorProduct = "_".date('Y-m-d H:i:s');

            file_put_contents($directory.'/media/VendorProduct/'."_".$VendorProduct, $canvasFile);

            $model = $this->_dataExample->create();
            $model->addData([
            // "email"          => $post['vendorEmail'],
   //          "password"    => $post['vendorPassword'],
   //          "vendorName"   => $post['vendorName'],
   //          "vendorPhoneNumber"    => $post['vendorPhoneNumber'],
   //          "vendorAddress"    => $post['vendorAddress'],
   //          "vendorZipCode"   => $post['vendorZipCode'],
   //          "vendorTown"    => $post['vendorTown'],
   //          "vendorCountry"   => $post['vendorCountry'],
   //          "vendorCity"    => $post['vendorCity'],
            "textColorSelector"   => $post['textColorSelector'],
            "vendorStoreName"  => $post['vendorStoreName'],
            "firstTagline"    => $post['firstTagline'],
            "textFontSelector"    => $post['textFontSelector'],
            "VendorProduct" => $VendorProduct,
            "Vendorlogo"    => $Vendorlogo
            
            ]);

            $saveData = $model->save();

            // if($saveData){
            //     $this->messageManager->addSuccess( __('Store Created Successfully!') );
            // }
            // header("location: '/list/product/manage'");return;
            // $this->_redirect('/list/product/manage');
            // return;
            // Redirect to your form page (or anywhere you want...)

            $CustomRedirectionUrl = $this->_url->getUrl().'list/product/manage/';
            $this->_redirect->setRedirect($CustomRedirectionUrl);
            return;

            // $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            // $resultRedirect->setPath("/list/product/manage/");

            return $resultRedirect;
        }
    }
}