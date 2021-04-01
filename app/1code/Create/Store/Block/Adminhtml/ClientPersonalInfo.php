<?php
namespace Create\Store\Block\Adminhtml;
class ClientPersonalInfo extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_clientPersonalInfo';/*block grid.php directory*/
        $this->_blockGroup = 'Create_Store';
        $this->_headerText = __('ClientPersonalInfo');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}
