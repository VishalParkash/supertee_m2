<?php

namespace User\Client\Model\ResourceModel;

/**
 * This class initiates seller payments
 */
class Payments extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    /**
     * Define main table
     */
    protected function _construct() {
        $this->_init ( 'supertee_client_payments', 'id' );
    }
}