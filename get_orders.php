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
|   Authors: Simon Becker, Boris Iakovenko, Philipp Dahse | eKomi           |
| Copyright: (c) 2008-2013, eKomi Ltd. All Rights Reserved.                 |
'--------------------------------------------------------------------------*/


require_once( 'includes/config.inc.php' );
require_once( 'includes/func.ekomi.php' );
require_once( 'includes/func.log.php' );
require_once( 'includes/class.phpmailer.php' );
require_once( 'includes/func.api.' . check_api_type() . '.php' );
check_auth();
startup();

define('ORDERS_STATUS', "'complete'"); // you can use multiple stati - comma separated in single quotes. example: define('ORDERS_STATUS', "'status_1','status_2','status_3'");
define('STORE_ID', '1');
define('READ_FROM', date('Y-m-d 00:00:00', time()-14*24*60*60));
define('ENABLE_PRODUCT_REVIEWS', 1); //0: No product reviews, 1: Product reviews
define('TEST_MODE', 0); //0: No test mode, 1: test_mode



send_ping( 'getorders_start' );
$count=0;


$sql = "
SELECT orders.`entity_id`, `customer_firstname`, `customer_lastname`, `customer_email`, history.`created_at`
	FROM
		`sales_flat_order` as orders
	JOIN
		`sales_flat_order_status_history` as history ON orders.`entity_id` = `parent_id`
WHERE 
	orders.`status` IN (".ORDERS_STATUS.") and `store_id` IN (".STORE_ID.") AND history.status IN (".ORDERS_STATUS.") AND history.created_at >= '".READ_FROM."'";
			


$get   = mysql_query($sql);
// Daten in Array speichern
while($fetch = mysql_fetch_assoc($get)) {

	// Kundenname vorbereiten
	$data[$fetch['entity_id']]['customer_first_name'] = $fetch['customer_firstname'];		        
	$data[$fetch['entity_id']]['customer_last_name']  = $fetch['customer_lastname'];	
	$data[$fetch['entity_id']]['customer_email']      = $fetch['customer_email'];		        
	$data[$fetch['entity_id']]['order_id']            = $fetch['entity_id'];	

}

if (TEST_MODE){
	echo '<pre>';
	var_dump($data);	
}
$num_orders = count( $data );
if ($num_orders ==0) {
	write_log(201, 'no orders found', true);
}
else {
	write_log(202, $num_orders .' orders found', true);
}



// build query and select customers ready to send
$sql = "SELECT `order_id`  FROM `" . EKOMI_DB_TABLE . "` WHERE 1";

$get_existing_orders = mysql_query($sql) OR handle_mysql_error(__FILE__, __LINE__, $sql);
unset($sql);

// loop thru
while( $_existing_orders = mysql_fetch_assoc( $get_existing_orders ) ) {

	// write to array
	$existing_orders[] = trim($_existing_orders['order_id']);

}




echo "<b>" . $num_orders . ' orders found.</b><br />';
flush();
foreach ( $data AS  $fetch_data) {

	//+~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+//
	// Kunde für den E-Mail Versand eintragen //
	//+~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~+//

	// Kunde schon eingetragen?


	if ( !empty($fetch_data) && !in_array( $fetch_data['order_id'], $existing_orders))	{

		if(ENABLE_PRODUCT_REVIEWS) {

			// get & save product data

			$sql   = "
			SELECT 
			  sku, name
			FROM
			  `sales_flat_order_item`
			WHERE
			  order_id = '" . $fetch_data['order_id'] ."'";

			$get_products   = mysql_query($sql) OR handle_mysql_error(__FILE__, __LINE__, $sql);

			// Daten in Array speichern
			while($fetch_products = mysql_fetch_assoc($get_products)) {
				
				$fetch_data['products'][$fetch_products['sku']] = str_replace(array(',', '"', "'", '|', "\r", "\n"), '', trim($fetch_products['name']));

			}

		}
		
		if (TEST_MODE){
			if (ENABLE_PRODUCT_REVIEWS){
				echo '<pre>';
				var_dump($fetch_data);
				exit;
			}
			else exit;
		}
		
		if (stripos($fetch_data['customer_email'], 'marketplace.amazon.') !== FALSE  || preg_match('~amazon@~', $fetch_data['customer_email'])){
			write_log(106, 'Skipping amazon email address: '. $fetch_data['customer_email'], false);
		}
		else {
		
		// Kunde eintragen
			$sql = "
			INSERT INTO
			  `" . EKOMI_DB_TABLE . "`
			SET
			  `order_id`      = '".mysql_real_escape_string($fetch_data['order_id'])."',
			  `first_name`    = '".mysql_real_escape_string($fetch_data['customer_first_name'])."',
			  `last_name`     = '".mysql_real_escape_string($fetch_data['customer_last_name'])."',
			  `email`         = '".mysql_real_escape_string($fetch_data['customer_email'])."',
			  `product_ids`   = '',
			  `product_names` = '".((count($fetch_data['products'])>0)?mysql_real_escape_string(serialize($fetch_data['products'])):'')."',
			  `time_read`     = ".time();
			  
			// filter wrong email addresses
			if ( !preg_match( '~[a-z0-9!#$%&\'*+/=?^_`{|}\~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}\~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?~i', $fetch_data['customer_email'])) {
				$sql .= ', `time_sent` = 0';
				write_log(104, 'Skipping incorrect email address: '. $fetch_data['customer_email'], false);                                       
			}   
			// end filter


			mysql_query($sql) OR handle_mysql_error(__FILE__, __LINE__, $sql);

			$existing_orders[]    = $fetch_data['order_id'];

			write_log(200, 'order added: '.$fetch_data['order_id'], false);
			$count++;
		}
		unset ($fetch_data['products'], $fetch_products);
		
	}
}

// log found mails
write_log(203, $count . ' orders added', true);

send_ping( 'getorders_success' );

?>
