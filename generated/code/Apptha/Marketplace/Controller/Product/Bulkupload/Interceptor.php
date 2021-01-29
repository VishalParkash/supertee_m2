<?php
namespace Apptha\Marketplace\Controller\Product\Bulkupload;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Product\Bulkupload
 */
class Interceptor extends \Apptha\Marketplace\Controller\Product\Bulkupload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Catalog\Helper\Category $categoryHelper, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState, \Magento\Backend\App\Action\Context $context, \Apptha\Marketplace\Helper\Marketplace $helperData)
    {
        $this->___init();
        parent::__construct($productRepository, $categoryHelper, $productFactory, $categoryFlatState, $context, $helperData);
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
