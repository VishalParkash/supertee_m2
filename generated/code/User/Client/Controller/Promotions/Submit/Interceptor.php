<?php
namespace User\Client\Controller\Promotions\Submit;

/**
 * Interceptor class for @see \User\Client\Controller\Promotions\Submit
 */
class Interceptor extends \User\Client\Controller\Promotions\Submit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Framework\App\Cache\StateInterface $cacheState, \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\SalesRule\Api\CouponRepositoryInterface $couponRepository, \Magento\SalesRule\Api\RuleRepositoryInterface $RuleRepository, \Magento\SalesRule\Model\CouponFactory $couponFactory, \Magento\SalesRule\Model\RuleFactory $ruleFactory, \Magento\Framework\Math\Random $random, \Magento\SalesRule\Api\Data\CouponGenerationSpecInterfaceFactory $generationSpecFactory, \Magento\SalesRule\Model\Service\CouponManagementService $couponManagementService, \Magento\SalesRule\Api\RuleRepositoryInterface $ruleRepository, \Magento\SalesRule\Api\Data\RuleInterfaceFactory $rule, \Magento\SalesRule\Api\Data\CouponInterface $coupon, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $cacheTypeList, $cacheState, $cacheFrontendPool, $resultPageFactory, $couponRepository, $RuleRepository, $couponFactory, $ruleFactory, $random, $generationSpecFactory, $couponManagementService, $ruleRepository, $rule, $coupon, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
