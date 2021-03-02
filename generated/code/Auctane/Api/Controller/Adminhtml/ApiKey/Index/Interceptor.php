<?php
namespace Auctane\Api\Controller\Adminhtml\ApiKey\Index;

/**
 * Interceptor class for @see \Auctane\Api\Controller\Adminhtml\ApiKey\Index
 */
class Interceptor extends \Auctane\Api\Controller\Adminhtml\ApiKey\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\App\Config\Storage\WriterInterface $configWriter, \Auctane\Api\Model\ApiKeyGenerator $apiKeyGenerator)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $configWriter, $apiKeyGenerator);
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
