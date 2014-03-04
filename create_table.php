<?php
/*~ create_table.php
.---------------------------------------------------------------------------.
|  Software: eKomi                                                          |
|   Version: 2.0.2                                                          |
|   Updated: 2010-10-19                                                     |
|   Contact: +49 30 2000 444 999 | support@ekomi.de                         |
|      Info: http://ekomi.de                                                |
|   Support: http://ekomi.de                                                |
| ------------------------------------------------------------------------- |
|    Author: Simon Becker | eKomi                                           |
| Copyright: (c) 2008-2010, eKomi Ltd. All Rights Reserved.                 |
'--------------------------------------------------------------------------*/


require_once( 'includes/config.inc.php' );
require_once( 'includes/func.ekomi.php' );
require_once( 'includes/func.log.php' );
require_once( 'includes/class.phpmailer.php' );
require_once( 'includes/func.api.' . check_api_type() . '.php' );

startup();


mysql_query('
CREATE TABLE IF NOT EXISTS `'.EKOMI_DB_TABLE.'` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`order_id` VARCHAR( 64 ) NOT NULL ,
`first_name` VARCHAR( 64 ) NOT NULL ,
`last_name` VARCHAR( 64 ) NOT NULL ,
`email` VARCHAR( 128 ) NOT NULL ,
`product_ids` VARCHAR( 512 ) NOT NULL ,
`product_names` TEXT NOT NULL ,
`time_read` INT NOT NULL ,
`time_sent` INT NULL DEFAULT NULL ,
`flag` TINYINT NOT NULL ,
INDEX ( `time_read` , `flag` ) ,
UNIQUE (
`order_id` 
)
) ENGINE = MYISAM
') or die(mysql_error());



mysql_query('INSERT INTO
  `'.EKOMI_DB_TABLE.'`
SET
  `order_id`   = "ektest_'.rand(10000,99999).'",
  `first_name` = "Max",
  `last_name`  = "Mustermann",
  `email`      = "emailtest@ekomi.de",
  `time_read`  = 0,
  `product_names` = \'a:1:{s:10:"eKomi_test";s:18:"eKomi product test";}\';
 ') or die(mysql_error()); 
  
  
echo 'Erfolgreich ausgeführt. Bitte diese Datei jetzt löschen!';

?>