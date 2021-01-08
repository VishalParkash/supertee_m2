<?php

namespace Turiknox\SampleImageUploader\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Turiknox\SampleImageUploader\Model\Blog', 'Turiknox\SampleImageUploader\Model\ResourceModel\Blog');
    }
}