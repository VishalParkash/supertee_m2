<?php
namespace Create\Store\Controller\Store\Save;

/**
 * Interceptor class for @see \Create\Store\Controller\Store\Save
 */
class Interceptor extends \Create\Store\Controller\Store\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Framework\App\Cache\StateInterface $cacheState, \Forms\Registration\Model\Session $session, \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Store\Model\Website $website, \Magento\Store\Model\Config\Importer\Processor\Create $storeCreateProcessor, \Magento\Catalog\Model\CategoryFactory $categoryFactory, \Magento\Store\Api\StoreRepositoryInterface $storeRepository, \Magento\Store\Model\ResourceModel\Group $groupResourceModel, \Magento\Store\Model\GroupFactory $groupFactory, \Magento\Store\Model\ResourceModel\Store $storeResourceModel, \Magento\Store\Model\StoreFactory $storeFactory, \Magento\Store\Model\ResourceModel\Website $websiteResourceModel, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $cacheTypeList, $cacheState, $session, $cacheFrontendPool, $resultPageFactory, $website, $storeCreateProcessor, $categoryFactory, $storeRepository, $groupResourceModel, $groupFactory, $storeResourceModel, $storeFactory, $websiteResourceModel, $websiteFactory, $eventManager, $resultJsonFactory);
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
