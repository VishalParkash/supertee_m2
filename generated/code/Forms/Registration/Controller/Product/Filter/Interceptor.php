<?php
namespace Forms\Registration\Controller\Product\Filter;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Product\Filter
 */
class Interceptor extends \Forms\Registration\Controller\Product\Filter implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Catalog\Model\ProductFactory $_productloader, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $CollectionFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->___init();
        parent::__construct($context, $_productloader, $productCollectionFactory, $CollectionFactory, $resultJsonFactory, $pageFactory);
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
    public function categoryFilter()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'categoryFilter');
        return $pluginInfo ? $this->___callPlugins('categoryFilter', func_get_args(), $pluginInfo) : parent::categoryFilter();
    }

    /**
     * {@inheritdoc}
     */
    public function priceFilter($productCollection)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'priceFilter');
        return $pluginInfo ? $this->___callPlugins('priceFilter', func_get_args(), $pluginInfo) : parent::priceFilter($productCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function brandFilter($productCollection)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'brandFilter');
        return $pluginInfo ? $this->___callPlugins('brandFilter', func_get_args(), $pluginInfo) : parent::brandFilter($productCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function sizeFilter($productCollection)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'sizeFilter');
        return $pluginInfo ? $this->___callPlugins('sizeFilter', func_get_args(), $pluginInfo) : parent::sizeFilter($productCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getActionFlag()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActionFlag');
        return $pluginInfo ? $this->___callPlugins('getActionFlag', func_get_args(), $pluginInfo) : parent::getActionFlag();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRequest');
        return $pluginInfo ? $this->___callPlugins('getRequest', func_get_args(), $pluginInfo) : parent::getRequest();
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResponse');
        return $pluginInfo ? $this->___callPlugins('getResponse', func_get_args(), $pluginInfo) : parent::getResponse();
    }
}
