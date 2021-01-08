<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Created By : Vishal Parkash
 */
namespace Turiknox\SampleImageUploader\Model;

class Category extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Turiknox\SampleImageUploader\Model\ResourceModel\Category');
    }
}