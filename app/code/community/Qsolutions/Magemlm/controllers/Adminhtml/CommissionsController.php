<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Qsolutions_Magemlm_Adminhtml_CommissionsController extends Mage_Adminhtml_Controller_Action {
    
    
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


    protected function _isAllowed()
    {
        return true;
    }
	
	
	public function payAction () {
		
		if ($data = $this->getRequest()->getPost()) {
			try {
				// assign post data
				$customerIds 	= $data['customerId'];
				$yearMonth		= $data['commissionDate'];
				Mage::getModel('magemlm/commissions')->payCommissions($customerIds, $yearMonth);
				
				$message = $this->__('Selected commissions were marked as paid');				
            	Mage::getSingleton('adminhtml/session')->addSuccess($message);
				$this->_redirect('*/*');
				
			} catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_initAction()
                        ->renderLayout();
				return;
            }
			$this->_redirect('*/*');
		}
		 
	}

}
