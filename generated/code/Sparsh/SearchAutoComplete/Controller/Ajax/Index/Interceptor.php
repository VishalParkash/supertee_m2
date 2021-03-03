<?php
namespace Sparsh\SearchAutoComplete\Controller\Ajax\Index;

/**
 * Interceptor class for @see \Sparsh\SearchAutoComplete\Controller\Ajax\Index
 */
class Interceptor extends \Sparsh\SearchAutoComplete\Controller\Ajax\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Search\Model\QueryFactory $queryFactory, \Sparsh\SearchAutoComplete\Block\Autocomplete $autocompleteBlock)
    {
        $this->___init();
        parent::__construct($context, $queryFactory, $autocompleteBlock);
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
