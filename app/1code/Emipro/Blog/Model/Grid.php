<?php
namespace Emipro\Blog\Model;
 
class Grid extends \Magento\Framework\Model\AbstractModel
{
    
    protected function _construct()
    {
        $this->_init('Emipro\Blog\Model\ResourceModel\Grid');
    }
}
 
?>