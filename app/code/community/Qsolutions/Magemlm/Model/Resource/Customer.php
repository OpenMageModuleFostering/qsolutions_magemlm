<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Qsolutions_Magemlm_Model_Resource_Customer extends Mage_Core_Model_Resource_Db_Abstract {
    //put your code here
    
    protected function _construct()
    {
        $this->_init('magemlm/customer' , 'magemlm_id');
    }
}

?>
