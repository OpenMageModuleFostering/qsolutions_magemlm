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

    DROP TABLE IF EXISTS {$this->getTable('magemlm_unilevel')};
    CREATE TABLE {$this->getTable('magemlm_unilevel')} (
      `unilevel_id`     	int(11) unsigned NOT NULL auto_increment,
      `level_name`      	varchar(255) NOT NULL ,
      `level_commission`   	varchar(255) NOT NULL ,
      PRIMARY KEY (`unilevel_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$installer->endSetup(); 