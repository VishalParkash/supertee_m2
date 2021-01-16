<?php
namespace Forms\Registration\Controller\Submit\Submit;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Submit\Submit
 */
class Interceptor extends \Forms\Registration\Controller\Submit\Submit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Forms\Registration\Model\DataExampleFactory $dataExample, \Magento\Framework\App\Filesystem\DirectoryList $directory_list, \Magento\Framework\Filesystem $fileSystem, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\Response\Http $redirect, \Magento\Framework\Controller\ResultFactory $result)
    {
        $this->___init();
        parent::__construct($context, $dataExample, $directory_list, $fileSystem, $url, $redirect, $result);
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
