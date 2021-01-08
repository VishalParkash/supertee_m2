<?php
namespace Turiknox\SampleImageUploader\Controller\Adminhtml\Image\Save;

/**
 * Interceptor class for @see \Turiknox\SampleImageUploader\Controller\Adminhtml\Image\Save
 */
class Interceptor extends \Turiknox\SampleImageUploader\Controller\Adminhtml\Image\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Registry $registry, \Turiknox\SampleImageUploader\Api\ImageRepositoryInterface $imageRepository, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, \Magento\Framework\Message\Manager $messageManager, \Turiknox\SampleImageUploader\Api\Data\ImageInterfaceFactory $imageFactory, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Turiknox\SampleImageUploader\Model\UploaderPool $uploaderPool, \Magento\Backend\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($registry, $imageRepository, $resultPageFactory, $dateFilter, $messageManager, $imageFactory, $dataObjectHelper, $uploaderPool, $context);
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
