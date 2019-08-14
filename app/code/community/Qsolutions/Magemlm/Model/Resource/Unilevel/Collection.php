<?php

/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Qsolutions_Magemlm_Model_Resource_Unilevel_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('magemlm/unilevel');
    }
	
}

?>
