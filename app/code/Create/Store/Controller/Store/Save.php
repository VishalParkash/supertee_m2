<?php
/**
 *
 * Copyright © 2015 Createcommerce. All rights reserved.
 */
namespace Create\Store\Controller\Store;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
use Magento\Framework\Controller\Result\JsonFactory;

use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\ResourceModel\Group;
use Magento\Store\Model\ResourceModel\Store;
use Magento\Store\Model\ResourceModel\Website;
use Magento\Store\Model\StoreFactory;
use Magento\Store\Model\WebsiteFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;




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
    protected $resultJsonFactory;
    protected $storeFactory;
    protected $groupFactory;
    protected $website;
    // protected $storeResourceModel;
    protected $storeCreateProcessor;
    protected $categoryFactory;

    protected $storeRepository;

    protected $addressDataFactory;

/**
 * @var \Magento\Customer\Api\AddressRepositoryInterface
 */
protected $addressRepository;

    // private $groupFactory;
    /**
     * @var Group
     */
    protected $groupResourceModel;
    /**
     * @var StoreFactory
     */
    // private $storeFactory;
    /**
     * @var Store
     */
    protected $storeResourceModel;
    /**
     * @var WebsiteFactory
     */
    protected $websiteFactory;
    /**
     * @var Website
     */
    protected $websiteResourceModel;


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
        \Forms\Registration\Model\Session $session,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        // \Magento\Store\Model\StoreFactory $storeFactory,
        // \Magento\Store\Model\GroupFactory $groupFactory,
        \Magento\Store\Model\Website $website,
        // \Magento\Store\Model\ResourceModel\Website $storeResourceModel,
        \Magento\Store\Model\Config\Importer\Processor\Create $storeCreateProcessor,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,


        Group $groupResourceModel,
        GroupFactory $groupFactory,
        Store $storeResourceModel,
        StoreFactory $storeFactory,
        Website $websiteResourceModel,
        WebsiteFactory $websiteFactory,
        ManagerInterface $eventManager,



        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        $this->categoryFactory = $categoryFactory;
        $this->addressDataFactory = $addressDataFactory;

        $this->storeRepository= $storeRepository;

        // $this->storeFactory = $storeFactory;
        // $this->groupFactory = $groupFactory;
        $this->website = $website;
        // $this->storeResourceModel = $storeResourceModel;
        $this->storeCreateProcessor = $storeCreateProcessor;

        $this->eventManager = $eventManager;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
        $this->storeFactory = $storeFactory;
        $this->storeResourceModel = $storeResourceModel;
        $this->websiteFactory = $websiteFactory;
        $this->websiteResourceModel = $websiteResourceModel;
    }
	
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {   
        $result = $this->resultJsonFactory->create();
        // $result = $this->resultJsonFactory->create(ResultFactory::TYPE_JSON);

        $data = $this->getRequest()->getPostValue();

        $storename = $data['storename'];
        $storeCode =  str_replace(" ", "_", $storename);

        if(!empty($data['ccid']) && ($data['ccid'] != 0)){
            $clientId = $data['ccid'];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();

            $themeTable = $connection->getTableName('store_clientpersonalinfo');

            $validateStoreName = "SELECT storename FROM " . $themeTable . " WHERE storename = '".$data['storename']."'  ORDER BY id DESC";
            $response = $connection->fetchAll($validateStoreName);

            if(!empty($response)){
               return $result->setData(['output' => "Store name not available"]);
            }
            
            

            // if($connection->query($sql)){
                $attribute = [
                'website_code' => 'base',
                'website_name' => 'Main Website',
                'group_name' => $storename,
                'store_code' => $storeCode,
                'store_name' => $storename." View",
                'is_active' => '1'
            ];

                try{
                    $categoryId = $data['storecategory'];
                    $store = $this->storeFactory->create();
                    $store->load($attribute['store_code']);
                    
                    if(!$store->getId()){
                        /** @var \Magento\Store\Model\Website $website */
                        $website = $this->websiteFactory->create();
                        $website->load($attribute['website_code']);
                        $website = $this->setWebID($website, $attribute);

                        /** @var \Magento\Store\Model\Group $group */
                        $group = $this->groupFactory->create();
                        $group->setWebsiteId($website->getWebsiteId());
                        $group->setName($attribute['group_name']);
                        $group->setCode($attribute['group_name']);
                        $group->setRootCategoryId($categoryId);
                        $this->groupResourceModel->save($group);

                        // $group = $this->groupFactory->create();
                        // $group->load($attribute['group_name'], 'name');

                        
                        $getCategoryName = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId);
                        $rootCategoryName =  $getCategoryName->getName();
                        
                        

                        // return $category->getId();


                        $store->setCode($attribute['store_code']);
                        $store->setName($attribute['store_name']);
                        $store->setWebsite($website);
                        $store->setGroupId($group->getId());
                        $store->setData('is_active', $attribute['is_active']);
                        $this->storeResourceModel->save($store);
                        $this->eventManager->dispatch('store_add', ['store' => $store]);
                        $store = $this->storeFactory->create();

                        $getStore = $this->storeRepository->get($storeCode);
                        $storeId = $getStore->getId(); // this is the store ID


                        $category = $this->categoryFactory->create();

                        if($categoryId != 0)
                            $category->load($categoryId);

                        // $category->setName($rootCategoryName . ' - Default Category');
                        $category->setName($rootCategoryName);
                        $category->setIsActive('1');
                        $category->setStoreId($storeId);

                        if($categoryId == 0)
                        {
                            $parentCategory = $this->categoryFactory->create();
                            $parentCategory->load(\Magento\Catalog\Model\Category::TREE_ROOT_ID);

                            $category->setDisplayMode(\Magento\Catalog\Model\Category::DM_PRODUCT);
                            $category->setPath($parentCategory->getPath());
                        }

                        $category->save();

                        // $this->session->setData("ClientStoreData", $store);

                        // $ClientStoreData = $this->session->getData("ClientStoreData");
                        $ClientPersonalData = $this->session->getData("ClientPersonalData");

                        // $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
                        // $storeId = $storeManager->getStore()->getId();

                        // echo "store id is ".$storeId;
                         
                        // $websiteId = $storeManager->getStore($storeId)->getWebsiteId();
                        $websiteId = $website->getWebsiteId();
                         
                        try {
                            $customer = $objectManager->get('\Magento\Customer\Api\Data\CustomerInterfaceFactory')->create();
                            $customer->setWebsiteId($websiteId);
                            $email = $ClientPersonalData['email'];
                            $customer->setEmail($email);
                            $customer->setFirstname($ClientPersonalData['name']);
                            $customer->setLastname($ClientPersonalData['name']);
                            $customer->setGroupId(3);
                            $customer->setStoreId($storeId);
                            $hashedPassword = $objectManager->get('\Magento\Framework\Encryption\EncryptorInterface')->getHash($ClientPersonalData['password'], true);
                         
                            $objectManager->get('\Magento\Customer\Api\CustomerRepositoryInterface')->save($customer, $hashedPassword);
                         
                            $customer = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
                            $customer->setWebsiteId($websiteId)->loadByEmail($email);

                            $addresss = $objectManager->get('\Magento\Customer\Model\AddressFactory');
                            $address = $addresss->create();

                            $getAddress = $ClientPersonalData['address'];
                            if(strpos($getAddress, " ")) {
                                $getAddress = $getAddress;
                            }else{
                                $getAddress = "#".$getAddress." ";
                            }
                            $addressArr = explode(" ", $getAddress, 2);


                            $address->setFirstname($ClientPersonalData['name'])
                                    ->setLastname($ClientPersonalData['name'])
                                    ->setCountryId($ClientPersonalData['country'])
                                    ->setCity($ClientPersonalData['city'])
                                    ->setPostcode($ClientPersonalData['zipcode'])
                                    ->setCustomerId($customer->getId())
                                    ->setStreet($addressArr)
                                    ->setIsDefaultBilling('1')
                                    ->setIsDefaultShipping('1')
                                    ->setSaveInAddressBook('1')
                                    ->setTelephone($ClientPersonalData['phone_number']);
                                    $address->save();

                                    $sql = "UPDATE " . $themeTable . " SET 
                                        storename = '".$data['storename']."', 
                                        storetype = '".$data['storetype']."', 
                                        storecategory = '".$data['storecategory']."', 
                                        iswebsiteavailable = '".$data['iswebsiteavailable']."', 
                                        storedomain = '".$data['storedomain']."', 
                                        issellingexperienced = '".$data['issellingexperienced']."', 
                                        monthlyvolume = '".$data['monthlyvolume']."', 
                                        mediumtocontact = 'google' 
                                        WHERE id= ".$clientId;
                                    $connection->query($sql);

                                if($connection->query($sql)){
                                    $getMyStore = $this->session->setData("getMyStore", $storeCode);
                                        $result->setData(['output' => true]);
                                }else{
                                    $result->setData(['output' => false]);
                                }
                        } catch (Exception $e) {
                            $result->setData(['output' => "Error Occured"]);
                        }
                    }else{
                        $result->setData(['output' => "Store name not available"]);
                    }
                }catch (Exception $e) {
                    $result->setData(['output' => "Error while creating store"]);
                }
        }else{
            $result->setData(['output' => "Client id is missing"]);
        }
        return $result;

  //       $this->resultPage = $this->resultPageFactory->create();  
		// return $this->resultPage;
        
    }

    // public function getWebsite()
    // {
    //     $group = $this->groupFactory->create();

    //     if($this->getGroupId())
    //     {
    //         $group->load($this->getGroupId());

    //         if($group->getId() && $group->getWebsiteId())
    //         {
    //             $website = $this->websiteFactory->create();
    //             $website->load($group->getWebsiteId());
    //             return $website;
    //         }
    //     }

    //     return null;
    // }

    // private function createOrUpdateGroup($groupId, $websiteId, $storeName, $storeCode)
    // {
    //     $group = $this->groupFactory->create();
    //     if($groupId)
    //         $group->load($groupId);

    //     $group->setWebsiteId($websiteId);
    //     $group->setName($storeName);
    //     $group->setCode($storeCode);
    //     $group->save();

    //     return $group;
    // }

    // private function createOrUpdateRootCategory($data, $categoryId = 0)
    // {
    //     $rootCategoryName = $data['name'];

    //     $category = $this->categoryFactory->create();

    //     if($categoryId != 0)
    //         $category->load($categoryId);

    //     $category->setName($rootCategoryName . ' - Default Category');
    //     $category->setIsActive($data['is_active']);
    //     $category->setStoreId(0);

    //     if($categoryId == 0)
    //     {
    //         $parentCategory = $this->categoryFactory->create();
    //         $parentCategory->load(\Magento\Catalog\Model\Category::TREE_ROOT_ID);

    //         $category->setDisplayMode(\Magento\Catalog\Model\Category::DM_PRODUCT);
    //         $category->setPath($parentCategory->getPath());
    //     }

    //     $category->save();

    //     return $category->getId();
    // }

    // private function createOrUpdateStore($group, $websiteId, $storeViewName, $storeViewCode, $data)
    // {
    //     $store = $this->storeFactory->create();
    //     if($group->getId() && $group->getDefaultStoreId())
    //         $store->load($group->getDefaultStoreId());

    //     $store->setName($storeViewName . ' - Store View');
    //     $store->setCode($storeViewCode);
    //     $store->setWebsiteId($websiteId);
    //     $store->setGroupId($group->getId());
    //     $store->setSortOrder($data['sort_order']);
    //     $store->setIsActive($data['is_active']);
    //     $store->save();

    //     return $store;
    // }

    public function setWebID($website, $attribute)
    {
        if(!$website->getId()){
            $website->setCode($attribute['website_code']);
            $website->setName($attribute['website_name']);
            $this->websiteResourceModel->save($website);
        }

        return $website;

    }


}
