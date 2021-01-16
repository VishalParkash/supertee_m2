<?php
namespace Magebees\Featuredproduct\Controller\Adminhtml\Manage\Save;

/**
 * Interceptor class for @see \Magebees\Featuredproduct\Controller\Adminhtml\Manage\Save
 */
class Interceptor extends \Magebees\Featuredproduct\Controller\Adminhtml\Manage\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $collection, $data);
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
