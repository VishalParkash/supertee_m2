<?php
namespace Apptha\Marketplace\Controller\Seller\CreatePost;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Seller\CreatePost
 */
class Interceptor extends \Apptha\Marketplace\Controller\Seller\CreatePost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Framework\UrlFactory $urlFactory, \Magento\Framework\Escaper $escaper, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Customer\Model\Account\Redirect $accountRedirect)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $accountManagement, $urlFactory, $escaper, $dataObjectHelper, $accountRedirect);
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
