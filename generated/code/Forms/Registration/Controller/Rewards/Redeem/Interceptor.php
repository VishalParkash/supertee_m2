<?php
namespace Forms\Registration\Controller\Rewards\Redeem;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Rewards\Redeem
 */
class Interceptor extends \Forms\Registration\Controller\Rewards\Redeem implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\ProductFactory $_productloader, \Forms\Registration\Model\Session $session, \Magento\Customer\Model\Session $customerSession, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $CollectionFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $_productloader, $session, $customerSession, $productCollectionFactory, $CollectionFactory, $resultJsonFactory, $pageFactory);
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
