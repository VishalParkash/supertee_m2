<?php

namespace Turiknox\SampleImageUploader\Model;

class Blog extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Turiknox\SampleImageUploader\Model\ResourceModel\Blog');
    }
}