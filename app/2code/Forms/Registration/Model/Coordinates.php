<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forms\Registration\Model;
use Magento\Framework\Model\AbstractModel;

class Coordinates extends \Magento\Framework\Model\AbstractModel {

    protected function _construct() {
        $this->_init('Forms\Registration\Model\ResourceModel\Coordinates');
    }
}