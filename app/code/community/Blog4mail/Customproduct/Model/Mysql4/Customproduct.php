<?php

class Blog4mail_Customproduct_Model_Mysql4_Customproduct extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the customproduct_id refers to the key field in your database table.
        $this->_init('customproduct/customproduct', 'customproduct_id');
    }
}