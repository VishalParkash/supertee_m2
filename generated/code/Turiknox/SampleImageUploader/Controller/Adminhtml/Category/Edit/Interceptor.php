<?php
namespace Turiknox\SampleImageUploader\Controller\Adminhtml\Category\Edit;

/**
 * Interceptor class for @see \Turiknox\SampleImageUploader\Controller\Adminhtml\Category\Edit
 */
class Interceptor extends \Turiknox\SampleImageUploader\Controller\Adminhtml\Category\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Backend\Model\Session $adminSession, \Turiknox\SampleImageUploader\Model\CategoryFactory $categoryFactory)
    {
        $this->___init();
        parent::__construct($context, $registry, $adminSession, $categoryFactory);
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
