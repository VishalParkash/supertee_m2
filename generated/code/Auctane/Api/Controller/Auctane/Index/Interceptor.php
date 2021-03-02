<?php
namespace Auctane\Api\Controller\Auctane\Index;

/**
 * Interceptor class for @see \Auctane\Api\Controller\Auctane\Index
 */
class Interceptor extends \Auctane\Api\Controller\Auctane\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Backend\Model\Auth\Credential\StorageInterface $storage, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Auctane\Api\Helper\Data $dataHelper, \Auctane\Api\Model\Action\Export $export, \Auctane\Api\Model\Action\ShipNotify $shipNotify, \Magento\Backend\Model\View\Result\RedirectFactory $redirectFactory, \Auctane\Api\Request\Authenticator $authenticator)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $storage, $scopeConfig, $dataHelper, $export, $shipNotify, $redirectFactory, $authenticator);
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
