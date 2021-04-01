<?php
namespace Forms\Registration\Controller\Cart\Coupon;

/**
 * Interceptor class for @see \Forms\Registration\Controller\Cart\Coupon
 */
class Interceptor extends \Forms\Registration\Controller\Cart\Coupon implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Cart $cart, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\Response\Http $redirect, \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository)
    {
        $this->___init();
        parent::__construct($context, $cart, $productRepository, $url, $redirect, $productAttributeRepository);
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
