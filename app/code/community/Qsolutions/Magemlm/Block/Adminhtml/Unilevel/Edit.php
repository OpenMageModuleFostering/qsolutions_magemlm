<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Qsolutions_Magemlm_Block_Adminhtml_Unilevel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
 
        $this->_objectId   = 'unilevel_id';
        $this->_blockGroup = 'magemlm';
        $this->_controller = 'adminhtml_unilevel';
        $this->_mode 	   = 'edit';
 
        $this->_updateButton('save', 'label', Mage::helper('magemlm')->__('Save Commission Level')); 
    }
 
 
    public function getHeaderText()
    {
        if (Mage::registry('unilevel_data') && Mage::registry('unilevel_data')->getId())
        {
            return Mage::helper('magemlm')->__('You are editing commission level: %s' ,Mage::registry('unilevel_data')->getLevelName() );
        } else {
            return Mage::helper('magemlm')->__('New comission level');
        }
        // return '';
    }
    
    
    protected function _prepareLayout() {
        
        if ($this->_blockGroup && $this->_controller && $this->_mode) {
            $this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'));
        }
        return parent::_prepareLayout();
    }
 
}
