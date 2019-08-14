<?php

/**
 * @category    Qsolutions
 * @package     Magemlm
 * @copyright   Copyright (c) 2013 Qsolutions Studio
 * @author		Jakub Winkler
 */

$installer = $this;
$installer->startSetup();


$installer->run("

    DROP TABLE IF EXISTS {$this->getTable('magemlm_commissions')};
    CREATE TABLE {$this->getTable('magemlm_commissions')} (
      `commission_id`     	int(11) unsigned NOT NULL auto_increment,
      `customer_id`      	int(11) NOT NULL ,
      `order_id`   			int(11) NOT NULL ,
      `created_at`    		datetime NULL, 
      `commission_level`    int(11), 
      `commission_value`    decimal(12,4),
      `commission_status`   tinyint NOT NULL, 
      PRIMARY KEY (`commission_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup(); 