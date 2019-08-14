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

    DROP TABLE IF EXISTS {$this->getTable('magemlm_customers')};
    CREATE TABLE {$this->getTable('magemlm_customers')} (
      `magemlm_id`      int(11) unsigned NOT NULL auto_increment,
      `customer_id`     int(11) NOT NULL ,
      `referrer_id`     varchar(255) NOT NULL ,
      `magemlm_image`   varchar(255) NOT NULL ,
      PRIMARY KEY (`magemlm_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup(); 