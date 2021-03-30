<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forms\Registration\Model\ResourceModel\Coordinates;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection{
    protected function _construct()
    {
        $this->_init('Forms\Registration\Model\Coordinates', 'Forms\Registration\Model\ResourceModel\Coordinates');
    }
}