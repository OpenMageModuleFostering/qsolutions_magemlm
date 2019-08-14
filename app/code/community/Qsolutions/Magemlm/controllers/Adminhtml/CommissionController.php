<?php

/**
 * @category    Qsolutions
 * @package     Magemlm
 * @copyright   Copyright (c) 2013 Qsolutions Studio
 * @author 		Jakub Winkler
 */

class Qsolutions_Magemlm_Adminhtml_CommissionController extends Mage_Adminhtml_Controller_Action {
    
    
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
        
        $this->_initAction()
                ->renderLayout();
    }


    protected function _isAllowed()
    {
        return true;
    }

}
