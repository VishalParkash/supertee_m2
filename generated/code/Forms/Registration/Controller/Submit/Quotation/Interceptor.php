<?php
namespace Forms\Registration\Controller\Submit\Quotation;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Submit\Quotation
 */
class Interceptor extends \Forms\Registration\Controller\Submit\Quotation implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Math\Random $mathRandom, \Magento\Framework\App\Filesystem\DirectoryList $directory_list, \Magento\Framework\Filesystem $fileSystem, \Magento\Customer\Model\Session $customer, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\Response\Http $redirect, \Forms\Registration\Model\Session $session, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Controller\ResultFactory $result)
    {
        $this->___init();
        parent::__construct($context, $messageManager, $resource, $mathRandom, $directory_list, $fileSystem, $customer, $url, $redirect, $session, $resultJsonFactory, $pageFactory, $scopeConfig, $result);
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
