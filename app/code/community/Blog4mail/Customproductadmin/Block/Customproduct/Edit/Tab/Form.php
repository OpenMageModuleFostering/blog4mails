<?php

class Blog4mail_Customproductadmin_Block_Customproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('customproduct_form', array('legend'=>Mage::helper('customproduct')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('customproduct')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('customproduct')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('customproduct')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('customproduct')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('customproduct')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('customproduct')->__('Content'),
          'title'     => Mage::helper('customproduct')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getCustomproductData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getCustomproductData());
          Mage::getSingleton('adminhtml/session')->setCustomproductData(null);
      } elseif ( Mage::registry('customproduct_data') ) {
          $form->setValues(Mage::registry('customproduct_data')->getData());
      }
      return parent::_prepareForm();
  }
}