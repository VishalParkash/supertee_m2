<?php
namespace User\Client\Model\ResourceModel\Payments;

/**
 * This class contains seller subscription plans model collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    /**
     * Define model & resource model
     */
    protected function _construct() {
        $this->_init ( 'User\Client\Model\Payments', 'User\Client\Model\ResourceModel\Payments' );
    }
}