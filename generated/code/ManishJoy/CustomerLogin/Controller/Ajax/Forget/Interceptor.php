<?php
namespace ManishJoy\CustomerLogin\Controller\Ajax\Forget;

/**
 * Interceptor class for @see \ManishJoy\CustomerLogin\Controller\Ajax\Forget
 */
class Interceptor extends \ManishJoy\CustomerLogin\Controller\Ajax\Forget implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Forms\Registration\Model\Session $session, \Magento\Framework\Json\Helper\Data $helper, \ManishJoy\CustomerLogin\Model\Customer $customerModel, \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\App\Request\Http $request, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Math\Random $mathRandom, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $session, $helper, $customerModel, $customerAccountManagement, $resultJsonFactory, $request, $resource, $mathRandom, $resultRawFactory);
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
