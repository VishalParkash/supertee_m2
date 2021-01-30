<?php
/**
 *
 * Copyright © 2015 Usercommerce. All rights reserved.
 */
namespace User\Client\Controller\Promotions;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

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

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SalesRule\Api\Data\RuleInterfaceFactory;


class Submit extends \Magento\Framework\App\Action\Action
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
    // private $RuleRepository;


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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CouponRepositoryInterface
     */

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var CouponInterface
     */
    protected $coupon;

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
        CouponManagementService $couponManagementService ,

        RuleRepositoryInterface $ruleRepository,
        RuleInterfaceFactory $rule,
        CouponInterface $coupon,
        LoggerInterface $logger
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

        $this->ruleRepository = $ruleRepository;
        $this->rule = $rule;
        $this->coupon = $coupon;
        $this->logger = $logger;


    }
    
    /**
     * Flush cache storage
     *
     */
    public function execute()
    {
        $newRule = $this->rule->create();
        $data = $this->getRequest()->getPostValue();

        // echo "<pre>";print_r($data);die;
        $newRule->setName($data['couponName'])
            ->setDescription($data['couponName'])
            ->setIsAdvanced(true)
            ->setStopRulesProcessing(false)
            ->setDiscountQty(20)
            ->setCustomerGroupIds([0, 1, 2])
            ->setWebsiteIds([1])
            ->setIsRss(1)
            ->setUsesPerCoupon(0)
            ->setDiscountStep(0)
            ->setCouponType(RuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
            ->setSimpleAction(RuleInterface::DISCOUNT_ACTION_FIXED_AMOUNT_FOR_CART)
            ->setDiscountAmount(20)
            ->setIsActive(true);

        try {
            $ruleCreate = $this->ruleRepository->save($newRule);
            //If rule generated, Create new Coupon by rule id
            if ($ruleCreate->getRuleId()) {
                $this->createCoupon($ruleCreate->getRuleId(), $data['couponCode']);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        $this->resultPage = $this->resultPageFactory->create();  
        return $this->resultPage;
    }
//         require __DIR__ . '/app/bootstrap.php';

//         $params = $_SERVER;
// $bootstrap = Bootstrap::create(BP, $params);
// $obj = $bootstrap->getObjectManager();
// $state = $obj->get('Magento\Framework\App\State');
// $state->setAreaCode('adminhtml');  


// $coupon['name'] = 'Test Rule';
// $coupon['desc'] = 'Test Rule';
// $coupon['start'] = date('Y-m-d');
// $coupon['end'] = '';
// $coupon['max_redemptions'] = 1;
// $coupon['discount_type'] ='by_percent';
// $coupon['discount_amount'] = 50;
// $coupon['flag_is_free_shipping'] = 'no';
// $coupon['redemptions'] = 1;
// $coupon['code'] ='OFF50'; 

// $shoppingCartPriceRule = $obj->create('Magento\SalesRule\Model\Rule');
// $shoppingCartPriceRule->setName($coupon['name'])
//         ->setDescription($coupon['desc'])
//         ->setFromDate($coupon['start'])
//         ->setToDate($coupon['end'])
//         ->setUsesPerCustomer($coupon['max_redemptions'])
//         ->setCustomerGroupIds(array('0','1','2','3',))
//         ->setIsActive(1)
//         ->setSimpleAction($coupon['discount_type'])
//         ->setDiscountAmount($coupon['discount_amount'])
//         ->setDiscountQty(1)
//         ->setApplyToShipping($coupon['flag_is_free_shipping'])
//         ->setTimesUsed($coupon['redemptions'])
//         ->setWebsiteIds(array('1'))
//         ->setCouponType(2)
//         ->setCouponCode($coupon['code'])
//         ->setUsesPerCoupon(NULL);
//         $shoppingCartPriceRule->save();


//         $this->resultPage = $this->resultPageFactory->create();  
//         return $this->resultPage;
        
    

    // public function execute1(\Magento\Framework\Event\Observer $observer)
    // {
    //     $customer = $observer->getEvent()->getCustomer();
    //     $rule =  $this->ruleFactory->create();
    //     $rule->setName('5% discount')
    //         ->setIsAdvanced(true)
    //         ->setStopRulesProcessing(false)
    //         ->setDiscountQty(10)
    //         // ->setCustomerGroupIds([$customer->getGroupId()])
    //         ->setWebsiteIds([1])
    //         ->setCouponType(RuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
    //         ->setSimpleAction(RuleInterface::DISCOUNT_ACTION_FIXED_AMOUNT_FOR_CART)
    //         ->setDiscountAmount(10)
    //         ->setIsActive(true);
        
    //     try{
    //         $resultRules = $this->RuleRepository->save($rule);
    //         $this->createCouponCode($resultRules);
    //     } catch (\Magento\Framework\Exception\LocalizedException $ex) {

    //     }
        
    // }


    private function createCouponCode(RuleInterface $rule)
    {
        $couponCode = $this->random->getRandomString(8);
        $coupon = $this->couponFactory->create();
        $coupon->setCode($couponCode)
                ->setIsPrimary(1)
                ->setRuleId($rule->getRuleId());
        $this->couponRepository->save($coupon);
    }

    public function createCoupon(int $ruleId, $CouponCode) {
        /** @var CouponInterface $coupon */
        $coupon = $this->coupon;
        $coupon->setCode('20FIXED')
            ->setIsPrimary(1)
            ->setRuleId($ruleId);

        /** @var CouponRepositoryInterface $couponRepository */
        $coupon = $this->couponRepository->save($coupon);
        return $coupon->getCouponId();
    }
}
