<?php
namespace Cadence\UrlDedup\Console\Command\CategoryUrlRewrites;

/**
 * Interceptor class for @see \Cadence\UrlDedup\Console\Command\CategoryUrlRewrites
 */
class Interceptor extends \Cadence\UrlDedup\Console\Command\CategoryUrlRewrites implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\App\State $appState)
    {
        $this->___init();
        parent::__construct($resource, $appState);
    }

    /**
     * {@inheritdoc}
     */
    public function run(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'run');
        return $pluginInfo ? $this->___callPlugins('run', func_get_args(), $pluginInfo) : parent::run($input, $output);
    }
}
