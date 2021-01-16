<?php
/**
 * Copyright © 2015 Create. All rights reserved.
 */
namespace Create\Store\Model\ResourceModel;

/**
 * ClientPersonalInfo resource
 */
class ClientPersonalInfo extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('store_clientpersonalinfo', 'id');
    }

  
}
