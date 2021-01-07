<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forms\Registration\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Coordinates extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('catalog_product_coordinates_sides', 'id');
    }
}