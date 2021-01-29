<?php
/**
 *
 * Copyright © 2015 Usercommerce. All rights reserved.
 */
namespace User\Client\Controller\Promotions;

use Magento\SalesRule\Api\CouponRepositoryInterface;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\SalesRule\Api\RuleRepositoryInterface;
use Magento\SalesRule\Model\CouponFactory;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Framework\Math\Random;
use Magento\SalesRule\Api\Data\CouponGenerationSpecInterfaceFactory;
use Magento\SalesRule\Model\Service\CouponManagementService;
use Magento\Framework\App\Bootstrap;


class Action extends \Magento\Framework\App\Action\Action
{


    /**
     * @var CouponGenerationSpecInterfaceFactory
     */
    private $generationSpecFactory;

    /**
     * @var CouponManagementService
     */
    private $couponManagementService;

    /**
     * @var Random
     */
    private $random;

    /**
     * @var CouponFactory
     */
    private $couponFactory;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var CouponRepositoryInterface
     */
    private $couponRepository;

    /**
     * @var RuleRepositoryInterface
     */
    private $RuleRepository;


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
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,

        CouponRepositoryInterface $couponRepository,
        RuleRepositoryInterface $RuleRepository,
        CouponFactory $couponFactory,   
        RuleFactory $ruleFactory,
        Random $random,
        CouponGenerationSpecInterfaceFactory $generationSpecFactory,
        CouponManagementService $couponManagementService 
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;

        $this->RuleRepository = $RuleRepository;
        $this->couponRepository = $couponRepository;
        $this->ruleFactory = $ruleFactory;
        $this->couponFactory = $couponFactory;
        $this->random = $random;
        $this->couponManagementService = $couponManagementService;
        $this->generationSpecFactory = $generationSpecFactory;
    }
    
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
        require __DIR__ . '/app/bootstrap.php';

        $params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');  


$coupon['name'] = 'Test Rule';
$coupon['desc'] = 'Test Rule';
$coupon['start'] = date('Y-m-d');
$coupon['end'] = '';
$coupon['max_redemptions'] = 1;
$coupon['discount_type'] ='by_percent';
$coupon['discount_amount'] = 50;
$coupon['flag_is_free_shipping'] = 'no';
$coupon['redemptions'] = 1;
$coupon['code'] ='OFF50'; 

$shoppingCartPriceRule = $obj->create('Magento\SalesRule\Model\Rule');
$shoppingCartPriceRule->setName($coupon['name'])
        ->setDescription($coupon['desc'])
        ->setFromDate($coupon['start'])
        ->setToDate($coupon['end'])
        ->setUsesPerCustomer($coupon['max_redemptions'])
        ->setCustomerGroupIds(array('0','1','2','3',))
        ->setIsActive(1)
        ->setSimpleAction($coupon['discount_type'])
        ->setDiscountAmount($coupon['discount_amount'])
        ->setDiscountQty(1)
        ->setApplyToShipping($coupon['flag_is_free_shipping'])
        ->setTimesUsed($coupon['redemptions'])
        ->setWebsiteIds(array('1'))
        ->setCouponType(2)
        ->setCouponCode($coupon['code'])
        ->setUsesPerCoupon(NULL);
        $shoppingCartPriceRule->save();


        $this->resultPage = $this->resultPageFactory->create();  
        return $this->resultPage;
        
    }

    public function execute1(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $rule =  $this->ruleFactory->create();
        $rule->setName('5% discount')
            ->setIsAdvanced(true)
            ->setStopRulesProcessing(false)
            ->setDiscountQty(10)
            // ->setCustomerGroupIds([$customer->getGroupId()])
            ->setWebsiteIds([1])
            ->setCouponType(RuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
            ->setSimpleAction(RuleInterface::DISCOUNT_ACTION_FIXED_AMOUNT_FOR_CART)
            ->setDiscountAmount(10)
            ->setIsActive(true);
        
        try{
            $resultRules = $this->RuleRepository->save($rule);
            $this->createCouponCode($resultRules);
        } catch (\Magento\Framework\Exception\LocalizedException $ex) {

        }
        
    }


    private function createCouponCode(RuleInterface $rule)
    {
        $couponCode = $this->random->getRandomString(8);
        $coupon = $this->couponFactory->create();
        $coupon->setCode($couponCode)
                ->setIsPrimary(1)
                ->setRuleId($rule->getRuleId());
        $this->couponRepository->save($coupon);
    }
}
