<?php
namespace Apptha\Marketplace\Controller\Order\Update;

/**
 * Interceptor class for @see \Apptha\Marketplace\Controller\Order\Update
 */
class Interceptor extends \Apptha\Marketplace\Controller\Order\Update implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Catalog\Model\ProductFactory $productFactory, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender, \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $shipmentSender, \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $productFactory, $invoiceSender, $shipmentSender, $shipmentFactory);
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
