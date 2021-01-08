<?php
namespace Turiknox\SampleImageUploader\Controller\Adminhtml\Image\MassDelete;

/**
 * Interceptor class for @see \Turiknox\SampleImageUploader\Controller\Adminhtml\Image\MassDelete
 */
class Interceptor extends \Turiknox\SampleImageUploader\Controller\Adminhtml\Image\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Registry $registry, \Turiknox\SampleImageUploader\Api\ImageRepositoryInterface $imageRepository, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter, \Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Turiknox\SampleImageUploader\Model\ResourceModel\Image\CollectionFactory $collectionFactory, $successMessage, $errorMessage)
    {
        $this->___init();
        parent::__construct($registry, $imageRepository, $resultPageFactory, $dateFilter, $context, $filter, $collectionFactory, $successMessage, $errorMessage);
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
