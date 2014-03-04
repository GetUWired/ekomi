<?php
/*~ ekomi.php
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

// report script start to ekomi
send_ping( 'ekomi_start' );

// get mail settings
$mail_settings = get_settings( );

// get customers that should get a mail
$customers = get_customers_to_send( $mail_settings['mail_delay'] );

if(count($customers)>0) {

	$count=0;
	
	// loop thru all customers
	foreach( $customers AS $customer ) {

		// send product and get list of product_ids
		$product_ids = manage_products( $customer );

		// request unique link for this customer
		$send_order = send_order( $customer, $product_ids );

		// send mail to customer
		$send_mail = send_mail( $customer, $send_order['link'], $mail_settings );

		// mark customer as sent
		set_sent( $customer );
		
		$count++;
	}

	// log sent mails
	write_log ( 102,  $count . ' mails sent', true );

}
else {

	write_log ( 102,  'no mails to send', true );
	
}


// report successful script run to ekomi
send_ping( 'ekomi_success' );

?>