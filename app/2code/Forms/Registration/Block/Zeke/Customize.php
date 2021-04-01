<?php
namespace Forms\Registration\Block\Zeke;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
class Customize extends \Magento\Framework\View\Element\Template
{
	protected $_productCollectionFactory;
    protected $imageHelper;
    protected $productFactory;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Image $imageHelper,
        ProductFactory $productFactory,
        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }
    
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(3); // fetching only 3 products
        return $collection;
    }

    public function getProductImageUrl($id)
    {
    try {
    $product = $this->productFactory->create()->load($id);
    } catch (NoSuchEntityException $e) {
    return 'Data not found';
    }
    $url = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
    return $url;
    }

    public function getAccessToken(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.zakeke.com/token",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&access_type=S2S",
          CURLOPT_HTTPHEADER => array(
        "Authorization: Basic MjkxNTU6UEw2cHZVdmUyRHp4Q2EteFJpMmN4OXFYX2pwZ1FlQjhnWjZXckc2elFSNC4=",
        "Cache-Control: no-cache",
        "Content-Type: application/x-www-form-urlencoded"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $response = json_decode($response, TRUE);
    // echo "<pre>";print_r(json_decode($response, TRUE));die;
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return $response['access_token'];
    }

            // return $this->_pageFactory->create();
        }
}