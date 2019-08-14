<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('cardnet')};
CREATE TABLE {$this->getTable('cardnet')} (
  `cardnet_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` varchar(255) NOT NULL default '',
  `timestamp` datetime NULL,
  `terminal_id` varchar(255) NOT NULL default '',
  `oid` varchar(255) NOT NULL default '',
  `status` varchar(255) NOT NULL default '',
  `fail_reason` varchar(255) NOT NULL default '',
  `fail_rc` varchar(255) NOT NULL default '',
  `approval_code` varchar(255) NOT NULL default '',
  `processor_response_code` varchar(255) NOT NULL default '',
  `cardnumber` varchar(255) NOT NULL default '',
  `currency` varchar(255) NOT NULL default '',
  `ccbin` varchar(255) NOT NULL default '',
  `tdate` date NULL,
  `txndate_processed` varchar(255) NOT NULL default '',
  `expyear` varchar(255) NOT NULL default '',
  `expmonth` varchar(255) NOT NULL default '',
  `refnumber` varchar(255) NOT NULL default '',
  `ccbrand` varchar(255) NOT NULL default '',
  `cccountry` varchar(255) NOT NULL default '',
  `chargetotal` varchar(255) NOT NULL default '',
  `txntype` varchar(255) NOT NULL default '',
  `response_hash` varchar(255) NOT NULL default '',
  `paymentMethod` varchar(2) NOT NULL default '',
  PRIMARY KEY (`cardnet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();

