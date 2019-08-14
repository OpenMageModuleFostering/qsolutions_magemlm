<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Qsolutions_Magemlm_CustomerController extends Mage_Core_Controller_Front_Action {
    
	protected function _getSession() {
		
        return Mage::getSingleton('customer/session');
    }
	
    
    protected function _prepareLayout() {
    	
        return parent::_prepareLayout();
    }
    

    protected function _initAction() {
    	
        $this->loadLayout();
        return $this;
    }


    public function indexAction() {
    	if (!($this->_getSession()->isLoggedIn())) {
            $this->_redirect('/');
            return;
        }
		
        $this->_initAction()
                ->renderLayout();
    }
	
	public function viewAction () {
		
		if (!($this->_getSession()->isLoggedIn())) {
            $this->_redirect('/');
            return;
        }
		
		$this->_initAction()
                ->renderLayout();
	}
	
	public function configAction () {
		
		if (!($this->_getSession()->isLoggedIn())) {
            $this->_redirect('/');
            return;
        }
		
		$this->_initAction()
                ->renderLayout();
	}
	
	
	public function commissionsAction () {
		
		if (!($this->_getSession()->isLoggedIn())) {
            $this->_redirect('/');
            return;
        }
		
		$this->_initAction()
                ->renderLayout();
	}
	
	
	public function planAction () {
		
		if (!($this->_getSession()->isLoggedIn())) {
            $this->_redirect('/');
            return;
        }
		
		$this->_initAction()
                ->renderLayout();
	}

}
