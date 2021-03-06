﻿<?php
// Config
require_once ('config.php');

// Startup
require_once (DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry ();

// Database 
$db = new DB (DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set ( 'db', $db );

$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `cat_permission` text COLLATE utf8_bin NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `store_permission` text COLLATE utf8_bin NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `vendor_permission` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `folder` varchar(128) COLLATE utf8_bin NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `user_date_start` date NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."user` ADD `user_date_end` date NOT NULL;");

$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `order_status_id` int(11) NOT NULL DEFAULT '0';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `commission` decimal(15,4) NOT NULL DEFAULT '0.0000';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `store_tax` decimal(15,4) NOT NULL DEFAULT '0.0000';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `vendor_tax` decimal(15,4) NOT NULL DEFAULT '0.0000';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `vendor_total` decimal(15,4) NOT NULL DEFAULT '0.0000';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `vendor_paid_status` tinyint(1) NOT NULL DEFAULT '0';");
$db->query ("ALTER TABLE `".DB_PREFIX."order_product` ADD `title` varchar(255) COLLATE utf8_bin NOT NULL;");

$db->query ("ALTER TABLE `".DB_PREFIX."download` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."information` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."order_history` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."attribute` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."attribute_group` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."option` ADD `vendor_id` int(11) NOT NULL;");
$db->query ("ALTER TABLE `".DB_PREFIX."coupon` ADD `vendor_id` int(11) NOT NULL;");

$db->query ("CREATE TABLE `".DB_PREFIX."order_shipping` 
(`order_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_paid_status` tinyint(4) NOT NULL,
  `order_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `weight` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`order_shipping_id`))
  ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."vendor` 
(`vendor_id` int(11) NOT NULL AUTO_INCREMENT,	
  `vproduct_id` int(11) NOT NULL,
  `ori_country` varchar(128) COLLATE utf8_bin NOT NULL,
  `product_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `shipping_method` int(2) NOT NULL DEFAULT '0',
  `prefered_shipping` int(2) NOT NULL DEFAULT '0',
  `shipping_cost` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `vtotal` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `product_url` text COLLATE utf8_bin NOT NULL,
  `vendor` int(11) NOT NULL,
  `wholesale` varchar(128) COLLATE utf8_bin NOT NULL,
  `date_add` datetime NOT NULL,
   PRIMARY KEY (`vendor_id`)) 
   ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=49;");

$db->query ("CREATE TABLE `".DB_PREFIX."signup_fee_history` 
(`signup_fee_id` int(11) NOT NULL AUTO_INCREMENT,	
  `user_id` int(11) NOT NULL,
  `signup_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `commission_type` tinyint(2) NOT NULL,
  `paid_status` tinyint(1) NOT NULL Default '0',
  `signup_plan` varchar(256) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL Default '0',
  `vendor_name` varchar(256) COLLATE utf8_bin NOT NULL,
  `username` varchar(256) COLLATE utf8_bin NOT NULL,
  `user_date_start` date NOT NULL,
  `user_date_end` date NOT NULL,
  `date_added` datetime NOT NULL,
   PRIMARY KEY (`signup_fee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=49;");

$db->query ("CREATE TABLE `".DB_PREFIX."vendors` 
(`vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vendor_name` varchar(256) COLLATE utf8_bin NOT NULL,
  `commission_id` int(11) NOT NULL,
  `product_limit_id` int(11) NOT NULL,
  `company` varchar(256) COLLATE utf8_bin NOT NULL,
  `company_id` varchar(64) COLLATE utf8_bin NOT NULL,
  `vendor_description` text COLLATE utf8_bin NOT NULL,
  `telephone` varchar(20) COLLATE utf8_bin NOT NULL,
  `fax` varchar(20) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `paypal_email` varchar(50) COLLATE utf8_bin NOT NULL,
  `iban` varchar(128) COLLATE utf8_bin NOT NULL,
  `bank_name` varchar(256) COLLATE utf8_bin NOT NULL,
  `bank_address` text COLLATE utf8_bin NOT NULL,
  `swift_bic` varchar(64) COLLATE utf8_bin NOT NULL,
  `tax_id` varchar(128) COLLATE utf8_bin NOT NULL,
  `accept_paypal` tinyint(2) NOT NULL,
  `accept_cheques` tinyint(2) NOT NULL,
  `accept_bank_transfer` tinyint(2) NOT NULL,
  `store_url` text COLLATE utf8_bin NOT NULL,
  `vendor_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(128) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(128) COLLATE utf8_bin NOT NULL,
  `address_1` varchar(128) COLLATE utf8_bin NOT NULL,
  `address_2` varchar(128) COLLATE utf8_bin NOT NULL,
  `city` varchar(128) COLLATE utf8_bin NOT NULL,
  `postcode` varchar(10) COLLATE utf8_bin NOT NULL,
  `country_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;");

$db->query ("CREATE TABLE `".DB_PREFIX."courier` 
(`courier_id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_name` varchar(256) COLLATE utf8_bin NOT NULL,
  `description` varchar(128) COLLATE utf8_bin NOT NULL,
  `courier_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`courier_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."commission` 
(`commission_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_limit_id` int(5) NOT NULL DEFAULT '1',
  `commission_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `commission_type` tinyint(4) NOT NULL, 
  `commission` varchar(64) COLLATE utf8_bin NOT NULL,
  `duration` tinyint(4) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`commission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."product_limit` 
(`product_limit_id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `product_limit` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`product_limit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."order_status_vendor_update` 
(`vendor_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `date_add` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."vendor_payment` 
(`payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `payment_info` longtext COLLATE utf8_bin NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_bin NOT NULL,
  `payment_status` tinyint(5) NOT NULL,
  `payment_amount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `payment_date` datetime NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."product_shipping` 
( `product_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `courier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `priority` int(5) NOT NULL,
  `shipping_rate` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `geo_zone_id` int(11) NOT NULL,
  PRIMARY KEY (`product_shipping_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("CREATE TABLE `".DB_PREFIX."vendor_discount` 
(`vendor_discount_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_bin NOT NULL,  
  `amount` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `tax` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `coupon_paid_status` tinyint(5) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  PRIMARY KEY (`vendor_discount_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

$db->query ("INSERT INTO `".DB_PREFIX."courier` (`courier_id`, `courier_name`, `description`, `courier_image`, `sort_order`, `date_add`) VALUES
(1, 'DHL', '3-7 Days', 'data/post/dhlpost_t.jpg', 0, '0000-00-00 00:00:00'),
(2, 'EMS', '5-10 Days', 'data/post/ems_t.jpg', 0, '0000-00-00 00:00:00'),
(3, 'Fedex', '3-7 Days', 'data/post/fedex_t.jpg', 0, '0000-00-00 00:00:00'),
(4, 'JNE', '3-7 Days', 'data/post/JNE.jpg', 0, '0000-00-00 00:00:00'),
(5, 'TNT', '3-15 Days', 'data/post/tnt_t.jpg', 0, '0000-00-00 00:00:00'),
(6, 'UPS', '3-7 Days', 'data/post/ups_t.jpg', 0, '0000-00-00 00:00:00'),
(7, 'Normal Mail', '3-45 Days', 'data/post/normal.jpg', 0, '0000-00-00 00:00:00'),
(8, 'China Post Air Mail', '15-30 Days', 'data/post/chinapost_t.jpg', 0, '0000-00-00 00:00:00'),
(9, 'Hong Kong Air Mail', '15-30 Days', 'data/post/hongkongpost_t.jpg', 0, '0000-00-00 00:00:00');");

$db->query ("INSERT INTO `".DB_PREFIX."commission` (`commission_id`, `product_limit_id`, `commission_name`, `commission_type`, `commission`, `duration`, `sort_order`, `date_add`) VALUES
(1,  '1', 'No Commission', '1', '0', '0', '0', '0000-00-00 00:00:00'),
(2,  '1', 'Gold', '0', '5', '0', '1', '0000-00-00 00:00:00'),
(3,  '1', 'Silver', '1', '20', '0', '2', '0000-00-00 00:00:00'),
(4,  '1', 'Bronze', '0', '30', '0', '3', '0000-00-00 00:00:00'),
(5,  '1', '$1.00/month (USD) - 1 Year', '5', '12', '1', '8', '0000-00-00 00:00:00'),
(6,  '4', '$1.50/month (USD) - 6 Months', '4', '9', '6', '1', '0000-00-00 00:00:00');");

$db->query ("INSERT INTO `".DB_PREFIX."product_limit` (`product_limit_id`, `package_name`, `product_limit`, `sort_order`, `date_add`) VALUES
(1, 'Unlimited', 99999, 0, '0000-00-00 00:00:00'),
(2, 'Trial 30 Days', 10, 3, '0000-00-00 00:00:00'),
(3, 'Gold', 1000, 1, '0000-00-00 00:00:00'),
(4, '6 Months', 999, 1, '0000-00-00 00:00:00');");

$db->query ("INSERT INTO `".DB_PREFIX."user_group` (`user_group_id`, `name`, `permission`) VALUES
(50, 'Vendor', 'a:2:{s:6:\"access\";a:17:{i:0;s:16:\"catalog/attr2ven\";i:1;s:22:\"catalog/attr2ven_group\";i:2;s:15:\"catalog/cat2ven\";i:3;s:16:\"catalog/down2ven\";i:4;s:16:\"catalog/info2ven\";i:5;s:15:\"catalog/opt2ven\";i:6;s:15:\"catalog/pro2ven\";i:7;s:22:\"catalog/vendor_profile\";i:8;s:18:\"common/filemanager\";i:9;s:13:\"design/layout\";i:10;s:24:\"report/pro2ven_purchased\";i:11;s:21:\"report/pro2ven_viewed\";i:12;s:25:\"report/vendor_transaction\";i:13;s:21:\"sale/contract_history\";i:14;s:13:\"sale/coup2ven\";i:15;s:17:\"sale/vendor_order\";i:16;s:18:\"user/user_password\";}s:6:\"modify\";a:17:{i:0;s:16:\"catalog/attr2ven\";i:1;s:22:\"catalog/attr2ven_group\";i:2;s:15:\"catalog/cat2ven\";i:3;s:16:\"catalog/down2ven\";i:4;s:16:\"catalog/info2ven\";i:5;s:15:\"catalog/opt2ven\";i:6;s:15:\"catalog/pro2ven\";i:7;s:22:\"catalog/vendor_profile\";i:8;s:18:\"common/filemanager\";i:9;s:13:\"design/layout\";i:10;s:24:\"report/pro2ven_purchased\";i:11;s:21:\"report/pro2ven_viewed\";i:12;s:25:\"report/vendor_transaction\";i:13;s:21:\"sale/contract_history\";i:14;s:13:\"sale/coup2ven\";i:15;s:17:\"sale/vendor_order\";i:16;s:18:\"user/user_password\";}}'),
(51, 'Vendor_Hide_Category', 'a:2:{s:6:\"access\";a:17:{i:0;s:16:\"catalog/attr2ven\";i:1;s:22:\"catalog/attr2ven_group\";i:2;s:15:\"catalog/cat2ven\";i:3;s:16:\"catalog/down2ven\";i:4;s:16:\"catalog/info2ven\";i:5;s:15:\"catalog/opt2ven\";i:6;s:15:\"catalog/pro2ven\";i:7;s:22:\"catalog/vendor_profile\";i:8;s:18:\"common/filemanager\";i:9;s:13:\"design/layout\";i:10;s:24:\"report/pro2ven_purchased\";i:11;s:21:\"report/pro2ven_viewed\";i:12;s:25:\"report/vendor_transaction\";i:13;s:21:\"sale/contract_history\";i:14;s:13:\"sale/coup2ven\";i:15;s:17:\"sale/vendor_order\";i:16;s:18:\"user/user_password\";}s:6:\"modify\";a:16:{i:0;s:16:\"catalog/attr2ven\";i:1;s:22:\"catalog/attr2ven_group\";i:2;s:15:\"catalog/cat2ven\";i:3;s:16:\"catalog/down2ven\";i:4;s:16:\"catalog/info2ven\";i:5;s:15:\"catalog/opt2ven\";i:6;s:15:\"catalog/pro2ven\";i:7;s:22:\"catalog/vendor_profile\";i:8;s:18:\"common/filemanager\";i:9;s:13:\"design/layout\";i:10;s:24:\"report/pro2ven_purchased\";i:11;s:21:\"report/pro2ven_viewed\";i:12;s:25:\"report/vendor_transaction\";i:13;s:21:\"sale/contract_history\";i:14;s:17:\"sale/vendor_order\";i:15;s:18:\"user/user_password\";}}');");

echo '<center><h1>Multi-Vendor/DropShipper Database Created Succesfull!</h1> <h2>Please delete this file.</h2></center>'; 
?>