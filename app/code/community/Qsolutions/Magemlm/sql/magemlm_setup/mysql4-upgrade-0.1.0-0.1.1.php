<?php

/**
 * @category    Qsolutions
 * @package     Magemlm
 * @copyright   Copyright (c) 2013 Qsolutions Studio
 * @author		Jakub Winkler
 */
 
$installer = $this;
$installer->startSetup();

$conn   = $installer->getConnection();
//$conn->addColumn($this->getTable('customer_entity'), 'magemlm_image', 'varchar(255) not null'); 

$installer->endSetup(); 