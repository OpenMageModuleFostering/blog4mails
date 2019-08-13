<?php

class Blog4mail_Customproduct_Model_Mysql4_Customproduct_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('customproduct/customproduct');
    }
}