<?php 
 
/**
 * Magemlm
 *
 * @category    Qsolutions
 * @package     Qsolutions_Magemlm
 * @copyright   Copyright (c) 2013 Q-Solutions  (http://www.qsolutions.com.pl)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Qsolutions_Magemlm_Block_Customer_Structure extends Mage_Core_Block_Template {
	  
	/**
     * Constructor. Set template.
     */
    public function __construct() {  
        parent::__construct();  
        $this->setTemplate('magemlm/structure.phtml');
      }  
}