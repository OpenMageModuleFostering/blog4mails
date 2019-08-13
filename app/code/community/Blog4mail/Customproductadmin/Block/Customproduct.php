<?php
class Blog4mail_Customproductadmin_Block_Adminhtml_Customproduct extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'customproductadmin';
    $this->_blockGroup = 'customproductadmin';
    $this->_headerText = Mage::helper('customproductadmin')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('customproductadmin')->__('Add Item');
    parent::__construct();
  }
}
