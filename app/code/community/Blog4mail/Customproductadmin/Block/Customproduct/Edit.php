<?php

class Blog4mail_Customproductadmin_Block_Customproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'customproductadmin';
        $this->_controller = 'customproductadmin';
        
        $this->_updateButton('save', 'label', Mage::helper('customproduct')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('customproduct')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('customproduct_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'customproduct_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'customproduct_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('customproduct_data') && Mage::registry('customproduct_data')->getId() ) {
            return Mage::helper('customproduct')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('customproduct_data')->getTitle()));
        } else {
            return Mage::helper('customproduct')->__('Add Item');
        }
    }
}
