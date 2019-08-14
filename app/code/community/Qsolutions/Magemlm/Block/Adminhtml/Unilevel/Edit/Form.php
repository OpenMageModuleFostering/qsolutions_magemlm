<?php

class Qsolutions_Magemlm_Block_Adminhtml_Unilevel_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
    	if (Mage::getSingleton('adminhtml/session')->getUnilevelId())
        {
            $data = Mage::getSingleton('adminhtml/session')->getUnilevelId();
            Mage::getSingleton('adminhtml/session')->getUnilevelId(null);
        }
        elseif (Mage::registry('unilevel_data'))
        {
            $data = Mage::registry('unilevel_data')->getData();
        }
        else
        {
            $data = array();
        }
 
        $form = new Varien_Data_Form(array(
                'id' 		=> 'edit_form',
                'action' 	=> $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' 	=> 'post',
                'enctype' 	=> 'multipart/form-data',
        ));
 
        $form->setUseContainer(true);
        $this->setForm($form);
 
        $fieldset = $form->addFieldset('s', array(
             'legend' 		=> 	Mage::helper('magemlm')->__('Commission Level Settings')
        ));
 
        $fieldset->addField('level_name', 'text', array(
             'label'     	=> Mage::helper('magemlm')->__('Level name'),
             'class'     	=> 'required-entry',
             'required'  	=> true,
             'name'      	=> 'level_name',
             'note'     	=> Mage::helper('magemlm')->__('System name of commission level. For administration purposes'),
        ));
		
		$fieldset->addField('level_commission', 'text', array(
             'label'     	=> Mage::helper('magemlm')->__('Level Commission:'),
             'class'     	=> 'required-entry validate-zero-or-greater',
             'required'  	=> true,
             'name'      	=> 'level_commission',
             'note'     	=> Mage::helper('magemlm')->__('value  in %.'),
        ));
		
        $form->setValues($data);	
 
        return parent::_prepareForm();
    }
}