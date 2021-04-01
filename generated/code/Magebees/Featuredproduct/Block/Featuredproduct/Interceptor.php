<?php
namespace Magebees\Featuredproduct\Block\Featuredproduct;

/**
 * Interceptor class for @see \Magebees\Featuredproduct\Block\Featuredproduct
 */
class Interceptor extends \Magebees\Featuredproduct\Block\Featuredproduct implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\App\ResourceConnection $resource, \Magento\Framework\Url\Helper\Data $urlHelper, \Magebees\Featuredproduct\Model\ResourceModel\Customcollection\Collection $fpmanualCollection, \Magento\Framework\Stdlib\DateTime\DateTime $datetime, \Magento\Sales\Model\Order\Status\History $orderstatus, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\CatalogInventory\Helper\Stock $stockHelper, \Magento\Framework\ObjectManagerInterface $objectManager, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $resource, $urlHelper, $fpmanualCollection, $datetime, $orderstatus, $productCollectionFactory, $stockHelper, $objectManager, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        return $pluginInfo ? $this->___callPlugins('getImage', func_get_args(), $pluginInfo) : parent::getImage($product, $imageId, $attributes);
    }
}
