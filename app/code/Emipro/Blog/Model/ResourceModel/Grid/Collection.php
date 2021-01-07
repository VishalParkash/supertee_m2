<?php
namespace Emipro\Blog\Model\ResourceModel\Grid;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
 
    
    protected function _construct()
    {
        $this->_init('Emipro\Blog\Model\Grid', 'Emipro\Blog\Model\ResourceModel\Grid');
    }

    public function getAvailableStatuses()
    {
       return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}