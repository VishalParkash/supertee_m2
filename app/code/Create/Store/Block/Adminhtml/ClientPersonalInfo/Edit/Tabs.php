<?php
namespace Create\Store\Block\Adminhtml\ClientPersonalInfo\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_clientpersonalinfo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('ClientPersonalInfo Information'));
    }
}