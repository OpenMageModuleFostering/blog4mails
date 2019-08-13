<?php

class Blog4mail_Customproduct_Model_Customproduct extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('customproduct/customproduct');
    }
}