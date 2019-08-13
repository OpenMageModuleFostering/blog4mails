<?php

class Blog4mail_Customproductadmin_Block_Customproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('customproductadmin_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('customproduct')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('customproduct')->__('Item Information'),
          'title'     => Mage::helper('customproduct')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('customproduct/adminhtml_customproduct_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
