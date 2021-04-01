<?php 
namespace Forms\Registration\Model\ResourceModel\DataExample;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
	public function _construct(){
		$this->_init("Forms\Registration\Model\DataExample","Forms\Registration\Model\ResourceModel\DataExample");
	}
}
 ?>