<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Qsolutions_Magemlm_Adminhtml_UnilevelController extends Mage_Adminhtml_Controller_Action {
    
    
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    

    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('multilevel');
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
		$msg 	= $this->_getSession()->getMessages(true); 
		$this->getLayout()->getMessagesBlock()->addMessages($msg);             
        $this->_initLayoutMessages('adminhtml/session')->renderLayout();
    }
	
	public function editAction()
    {
    	$id = $this->getRequest()->getParam('id', null);
        $model = Mage::getModel('magemlm/unilevel');
        if ($id) {
            $model->load((int) $id);
            if ($model->getId()) {
                $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
                if ($data) {
                    $model->setData($data)->setId($id);
                }
            } else {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magemlm')->__('Level does not exists'));
                $this->_redirect('*/*/');
            }
        }
		
        Mage::register('unilevel_data', $model);
        $this->loadLayout();        
        $this->_initAction()
                ->renderLayout();
    }


    protected function _isAllowed()
    {
        return true;
    }
	
	
	public function deleteAction () {
		
		$id 	= $this->getRequest()->getParam('id');
		try {
            if ($id) {
            	$model 	= Mage::getModel('magemlm/unilevel')->load($id , 'unilevel_id');
				$model->delete();
					
				$message = $this->__('Marketing level deleted');				
            	Mage::getSingleton('adminhtml/session')->addSuccess($message);
				$this->_redirect('*/*/');
				return;
			}
				
		} catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            if ($model && $model->getId()) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
            } else {
                $this->_redirect('*/*/');
            }
			return;
        }
		
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magemlm')->__('No data found to delete'));
        $this->_redirect('*/*/');
	}
	
	
	public function saveAction () {
		if ($data = $this->getRequest()->getPost())
        {
            $model 	= Mage::getModel('magemlm/unilevel');
            $id 	= $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id , 'unilevel_id');
            }
            $model->setData($data);
			
 
            Mage::getSingleton('adminhtml/session')->setFormData($data);
            try {
                if ($id) {
                    $model->setId($id);
                }
                $model->save();
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('magemlm')->__('Data not found'));
                }
 
                $message = $this->__('Data saved');				
            	Mage::getSingleton('adminhtml/session')->addSuccess($message);
                Mage::getSingleton('adminhtml/session')->setFormData(false);
 
                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
 
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
            }
 
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magemlm')->__('No data found to save'));
        $this->_redirect('*/*/');
	}
	

}
