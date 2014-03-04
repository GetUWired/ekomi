<?php
ini_set('error_reporting', E_ALL);
error_reporting(1);
error_reporting(E_ALL);

/*~ get_orders.php
.---------------------------------------------------------------------------.
|  Software: eKomi - get_orders module                                      |
|   Version: 2.0.2                                                          |
|Compatible: eKomi Core 2.0.0 - 2.0.2                                       |
|   Updated: 2013-03-15                                                     |
|   Contact: +49 30 2000 444 999 | support@ekomi.de                         |
|      Info: http://ekomi.de                                                |
|   Support: http://ekomi.de                                                |
| ------------------------------------------------------------------------- |
|   Authors: Simon Becker, Boris Iakovenko, Philipp Dahse,                  |
|            José Antonio Martín García   |  eKomi                          |
| Copyright: (c) 2008-2013, eKomi Ltd. All Rights Reserved.                 |
'--------------------------------------------------------------------------*/


require_once( 'includes/config.inc.php' );
require_once( 'includes/func.ekomi.php' );
require_once( 'includes/func.log.php' );
require_once( 'includes/class.phpmailer.php' );
require_once( 'includes/func.api.' . check_api_type() . '.php' );
check_auth();
startup();


$sql = "SELECT *  FROM `" . EKOMI_DB_TABLE . "`";

$get_content_db = mysql_query($sql);

// loop thru
while( $content = mysql_fetch_assoc( $get_content_db ) ) {

	// write to array
	echo '<br> </br>';
	var_dump ($content);
	echo '<br> </br>';

}
?>