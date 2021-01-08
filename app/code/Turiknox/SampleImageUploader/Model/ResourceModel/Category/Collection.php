<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Vishal Parkash
 */
namespace Turiknox\SampleImageUploader\Model\ResourceModel\Category;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Turiknox\SampleImageUploader\Model\Category', 'Turiknox\SampleImageUploader\Model\ResourceModel\Category');
    }
}