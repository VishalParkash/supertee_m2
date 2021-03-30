<?php
namespace Forms\Registration\Controller\Order\Index;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Order\Index
 */
class Interceptor extends \Forms\Registration\Controller\Order\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Api\OrderManagementInterface $orderMgt, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\Response\Http $redirect)
    {
        $this->___init();
        parent::__construct($context, $orderMgt, $messageManager, $url, $redirect);
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
