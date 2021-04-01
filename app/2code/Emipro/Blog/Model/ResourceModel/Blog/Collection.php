<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Emipro\Blog\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection{
    protected function _construct()
    {
        $this->_init('Emipro\Blog\Model\Blog', 'Emipro\Blog\Model\ResourceModel\Blog');
    }
}