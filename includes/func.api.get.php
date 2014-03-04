<?

function get_settings() {

$api          = 'http://api.ekomi.de/v2/getSettings?auth='.EKOMI_API_ID.'|'.EKOMI_API_KEY.'&version='.EKOMI_VERSION;
$get_settings = file_get_contents($api);
$settings     = unserialize( $get_settings );

check_settings($settings);

if(!empty(EKOMI_MAIL_SENDER)) {
    $settings['mail_from_email'] = EKOMI_MAIL_SENDER;
}

return $settings;

}


function send_order( $customer ) {

$api          = 'http://api.ekomi.de/v2/putOrder?auth='.EKOMI_API_ID.'|'.EKOMI_API_KEY.'&version='.EKOMI_VERSION.'&order_id='.$customer['order_id'].'&product_ids='.$customer['product_ids'];
$send_order   = file_get_contents($api);
$ret          = unserialize( $send_order );

// send products

return $ret;

}

function send_ping( $type ) {

$api          = 'http://api.ekomi.de/v2/putPing?auth='.EKOMI_API_ID.'|'.EKOMI_API_KEY.'&version='.EKOMI_VERSION.'&type='.$type;
$send_ping    = file_get_contents($api);
$ret          = unserialize( $send_ping );

return true;

}


function send_log( $type, $errno, $errstr ) {

$api          = 'http://api.ekomi.de/v2/putLog?auth='.EKOMI_API_ID.'|'.EKOMI_API_KEY.'&version='.EKOMI_VERSION.'&level='.$type.'&errnum='.$errno.'&errstr='.urlencode($errstr);
$send_log     = file_get_contents($api);
$ret          = unserialize( $send_log );

return true;

}
?>