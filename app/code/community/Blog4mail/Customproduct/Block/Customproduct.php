<?php
class Blog4mail_Customproduct_Block_Customproduct extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCustomproduct()     
     { 
        if (!$this->hasData('customproduct')) {
            $this->setData('customproduct', Mage::registry('customproduct'));
        }
        return $this->getData('customproduct');
        
    }
}