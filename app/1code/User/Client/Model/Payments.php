<?php
namespace User\Client\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * This class initiates subscription profiles model
 */
class Payments extends AbstractModel {
    /**
     * Define resource model
     */
    protected function _construct() {
        $this->_init ( 'User\Client\Model\ResourceModel\Payments' );
    }
}