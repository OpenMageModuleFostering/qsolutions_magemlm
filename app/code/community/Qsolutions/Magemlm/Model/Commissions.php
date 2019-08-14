<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Qsolutions_Magemlm_Model_Commissions extends Mage_Core_Model_Abstract {

    protected function _construct()
    {
        $this->_init('magemlm/commissions');
    }
	
	
	
	public function calculateCommissions ($customerId , $orderId, $orderSum) {
		// get all commision levels in revert order
		$uniLevelCommision 		= Mage::getModel('magemlm/unilevel')->getCollection()->setOrder('unilevel_id' , 'desc');
		$customerStructure 		= Mage::getModel('magemlm/commissions')->getCustomerStructure($customerId); // get array of customers up to store view
		$structureCount			= count($customerStructure);
		$commissionStructure 	= Mage::getModel('magemlm/commissions')->getCommissionStructure();
		
		$i = 0 ;
		foreach ($customerStructure as $customer) {
			// check if there is commission for this level
			if (isset($commissionStructure[$i])) {
				$commissionValue 	=  	$orderSum * $commissionStructure[$i] / 100; // remeber we save commission levels in %
				
				/// set data and save our commission - this is cool :-)
				$commission 		= Mage::getModel('magemlm/commissions');
				$commission ->setCustomerId($customer)
							->setOrderId   ($orderId)
							->setCommissionValue ($commissionValue)
							->setCreatedAt(date('Y-m-d H:i:s'))
							->setCommissionLevel(Mage::helper('magemlm')->getCustomerLevel($customerId))
							->save();	
			} else {
				break;
			}
			// go to next item
			$i++;
		}
	}
	
	
	/**
	 * getting customer structure
	 * @param customerId - customer id
	 * @return array of customer id's
	 */
	public function getCustomerStructure($customerId) {
		
		Mage::helper('magemlm')->getCustomerReferralId($customerId);
		$customerArray = array ();
		
		while (Mage::helper('magemlm')->getCustomerReferralId($customerId) || strpos($customerId , "store") ) {
			$customerId = Mage::helper('magemlm')->getCustomerReferralId($customerId);
			
			$pos = strpos($customerId, 'store');
			if ($pos !== false)  {
				break ; 
			}
			$customerArray[] = $customerId;
		}
		return $customerArray; 
	}
	
	
	/**
	 * getting commission structure
	 * @return array of commission values
	 */
	public function getCommissionStructure() {
		
		$commissionArray	= array ();
		$uniLevelCommision 	= Mage::getModel ('magemlm/unilevel')->getCollection()->setOrder('unilevel_id' , 'asc');

		foreach ($uniLevelCommision as $level) {
			$commissionArray[] = $level->getLevelCommission();
		}
		
		return $commissionArray;
	}


	/**
	 * @return double - commission count
	 */
	public function getCommissionSummary ($customerId) {
		$resource 		= Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
        $query 			= 'select SUM(commission_value) as sum from magemlm_commissions where customer_id = "'. $customerId . '" ';
        $result 		= $readConnection->fetchOne($query);
		return $result;
	}
	
	
	public function calculateCommission ($yearMonth , $paid = 0) {
		$resource 		= Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
        $query 			= ' select magemlm_commissions.customer_id as customer_id, 
						       magemlm_commissions.created_at as created_at, 
						       sum(commission_value) as sum , 
							concat( 
							    (select value from customer_entity_varchar where customer_entity_varchar.entity_id = magemlm_commissions.customer_id and attribute_id = 1 limit 1) , " " , 
							    (select value from customer_entity_varchar where customer_entity_varchar.entity_id = magemlm_commissions.customer_id and attribute_id = 2 limit 1)  
							) as customerName ,
							customer_entity.email as email, 
							commission_status 
							from magemlm_commissions
							    join customer_entity on customer_entity.entity_id = magemlm_commissions.customer_id
									where magemlm_commissions.created_at like "' . $yearMonth . '%" 
										and magemlm_commissions.commission_status = "' . $paid . '" 
											group by customer_id; ';
		$result 		= $readConnection->fetchAll($query);
		return 		$result;
	}


	public function payCommissions ($customerIdArray , $yearMonth) {
		
		foreach ($customerIdArray as $customerId) {
			$resource 		 = Mage::getSingleton('core/resource');
			$writeConnection = $resource->getConnection('core_read');
			$query 			= 'update magemlm_commissions 
									set magemlm_commissions.commission_status = "1" 
    									where magemlm_commissions.customer_id = "' . $customerId . '" 
         									and magemlm_commissions.created_at like "' . $yearMonth . '%"';
			$writeConnection->query($query);
		}
	}
    
}
