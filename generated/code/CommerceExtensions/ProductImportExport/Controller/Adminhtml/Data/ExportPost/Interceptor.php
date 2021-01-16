<?php
namespace CommerceExtensions\ProductImportExport\Controller\Adminhtml\Data\ExportPost;

/**
 * Interceptor class for @see \CommerceExtensions\ProductImportExport\Controller\Adminhtml\Data\ExportPost
 */
class Interceptor extends \CommerceExtensions\ProductImportExport\Controller\Adminhtml\Data\ExportPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\ResourceConnection $resourceConnection, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Module\Manager $moduleManager, \Magento\Framework\App\ProductMetadataInterface $ProductMetadataInterface, \Magento\Framework\Url $frameworkUrl, \Magento\Store\Model\StoreManager $storeManager, \Magento\Catalog\Model\CategoryFactory $categoryFactory, \Magento\CatalogInventory\Model\StockRegistry $stockRegistry, \Magento\Tax\Model\ClassModel $taxClassModel, \Magento\Catalog\Model\Product $productModel, \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProduct, \Magento\Downloadable\Model\Link $downloadableLink, \Magento\Downloadable\Model\Sample $downloadableSample, \Magento\Bundle\Model\Option $bundleOption, \Magento\Bundle\Model\Selection $bundleSelection, \Magento\Eav\Model\Entity\Attribute\Set $attributeSet, \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $productAttributeCollection, \Magento\Framework\App\Filesystem\DirectoryList $directoryList)
    {
        $this->___init();
        parent::__construct($context, $resourceConnection, $fileFactory, $moduleManager, $ProductMetadataInterface, $frameworkUrl, $storeManager, $categoryFactory, $stockRegistry, $taxClassModel, $productModel, $configurableProduct, $downloadableLink, $downloadableSample, $bundleOption, $bundleSelection, $attributeSet, $productAttributeCollection, $directoryList);
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
