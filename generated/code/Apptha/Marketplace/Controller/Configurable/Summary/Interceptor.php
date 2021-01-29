<?php
namespace Apptha\Marketplace\Controller\Configurable\Summary;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Configurable\Summary
 */
class Interceptor extends \Apptha\Marketplace\Controller\Configurable\Summary implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Result\PageFactory $resultFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($resultFactory, $layoutFactory, $context);
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
