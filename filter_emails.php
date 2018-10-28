<?php
/*~ 
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

check_auth();
startup();

set_time_limit(0);


// build query and select customers ready to send
$sql = "SELECT `id`, `email`  FROM `" . EKOMI_DB_TABLE . "` WHERE `time_sent` IS NULL";

$get_customers = mysql_query($sql) OR handle_mysql_error(__FILE__, __LINE__, $sql);
unset($sql);

// loop thru
while( $customer = mysql_fetch_assoc( $get_customers ) ) {

	//check if email is valid
	if ( !preg_match( '~^([a-z0-9!#$%&\'*+/=?^_`{|}\~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}\~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)$~i', $customer['email'] ) )

	{

		
//Please Change/Update to MySQLi		
		
mysql_query('update
`'.EKOMI_DB_TABLE.'`
SET
`time_sent`  = 0
WHERE
`email`   = "'.mysql_real_escape_string($customer['email']).'"
') or die(mysql_error()); 

		echo "deleting '".$customer['email']."'\r\n";
	}
}

?>
