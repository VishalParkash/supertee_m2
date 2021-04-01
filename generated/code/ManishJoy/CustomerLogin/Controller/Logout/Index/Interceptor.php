<?php
namespace ManishJoy\CustomerLogin\Controller\Logout\Index;

/**
 * Interceptor class for @see \ManishJoy\CustomerLogin\Controller\Logout\Index
 */
class Interceptor extends \ManishJoy\CustomerLogin\Controller\Logout\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Registry $registry, \Magento\Framework\UrlInterface $url, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Controller\ResultFactory $resultFactory)
    {
        $this->___init();
        parent::__construct($registry, $url, $customerSession, $resultFactory);
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
