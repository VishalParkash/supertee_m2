<?php
namespace Auctane\Api\Console\Command\DebugManagementCommand;

/**
 * Interceptor class for @see \Auctane\Api\Console\Command\DebugManagementCommand
 */
class Interceptor extends \Auctane\Api\Console\Command\DebugManagementCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\Storage\WriterInterface $config)
    {
        $this->___init();
        parent::__construct($config);
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
