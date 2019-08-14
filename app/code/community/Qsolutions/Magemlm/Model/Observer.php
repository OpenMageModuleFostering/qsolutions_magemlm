<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Qsolutions_Magemlm_Model_Observer {

    public function __construct () {
        
    } // public function __construct () {
    
    public function getLink(Varien_Event_Observer $observer) {
        
		if (isset ($_GET['refId']) ) {
			$cookie = Mage::getSingleton('core/cookie');
			$cookie->set('refId', $_GET['refId'] ,time()+86400,'/');
		}
    } // public function registerPartner($observer) {
    
    public function saveCustomerMlmData(Varien_Event_Observer $observer) {
        
		$customer   = $observer->getEvent()->getCustomer();
        $customerId = $customer->getId();
        $referrerId = Mage::app()->getRequest()->getPost('magemlm_referrer');
        
        // load magemlm / customer model
        $customerMagemlm   = Mage::getModel('magemlm/customer')->load($customerId , 'customer_id');
        
        if(isset($_FILES['magemlm_customer_picture']['name']) and (file_exists($_FILES['magemlm_customer_picture']['tmp_name']))) {
            try {
                
                $ext = pathinfo($_FILES['magemlm_customer_picture']['name'], PATHINFO_EXTENSION);
                
                $uploader = new Varien_File_Uploader('magemlm_customer_picture');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything

                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $path = Mage::getBaseDir('media') . DS . 'magemlm' . DS;

                $fileName = $customer->getId() . '_' . date('Ymdhis') . '.' . $ext;
                $uploader->save($path, $fileName); // save file
                
                $customerMagemlm->setCustomerId($customerId);
                $customerMagemlm->setReffererId($referrerId);
                $customerMagemlm->setMagemlmImage($fileName);
                $customerMagemlm->save();

            	} catch(Exception $e) {
        	} 
       } else {
          	$customerMagemlm->setCustomerId($customerId);
          	$customerMagemlm->setReferrerId($referrerId);
          	$customerMagemlm->save();   
      	}
    }


	public function registerCustomer (Varien_Event_Observer $observer) {
        
		$customer   = $observer->getEvent()->getCustomer();
        $customerId = $customer->getId();
        $referrerId = Mage::getSingleton('core/cookie')->get('refId');
		
        $customerMagemlm   = Mage::getModel('magemlm/customer')->load($customerId , 'customer_id');

       	$customerMagemlm->setCustomerId($customerId);
       	$customerMagemlm->setReferrerId($referrerId);
      	$customerMagemlm->save();   
    }
    		
    	
    
    
    public function getCustomer() {
        return Mage::registry('customer');
    } // public function getCustomer() {
    	
    		
    // observer function to get Compensation data
    public function saveCompensation(Varien_Event_Observer $observer) {
    	
		$order 		= new Mage_Sales_Model_Order();
    	$orderId  	= Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$order->loadByIncrementId($orderId);
		
		$orderPriceExldTax 	= $order->getBaseSubtotal(); // calculate commission for price WITHOUT TAX 
		Mage::getModel('magemlm/commissions')->calculateCommissions(Mage::helper('customer')->getCustomer()->getId() , $orderId, $orderPriceExldTax);
		
    }
    
}