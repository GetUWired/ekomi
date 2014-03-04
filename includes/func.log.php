<?

function write_log ($errno, $errstr, $log_external = false, $type='INFO') {

    $handle_log = fopen( EKOMI_LOG_FILE, 'a' ); // w = open file for writing
    fwrite($handle_log, '[' . date('Y-m-d H:i:s') . '] '. $errno .': '.$errstr ."\n" );
    fclose($handle_log);

    if($log_external) {
        send_log($type, $errno, $errstr);
    }

}


function handle_error ($errstr, $exit = false) {

write_log(500, 'ERROR: '. $errstr, true, 'ERROR');



// send mail
$mail           = new PHPMailer();

// set smtp settings if smtp mode is enables
if(EKOMI_SMTP_MODE == 1){

    $mail->Host     = EKOMI_SMTP_SERVER . ':' . EKOMI_SMTP_PORT;
    $mail->Mailer   = "smtp";
    $mail->SMTPAuth = true;
    $mail->Username = EKOMI_SMTP_USER;
    $mail->Password = EKOMI_SMTP_PASS;

}

$mail->From     = 'Error-Report';
$mail->FromName = 'error@feedback-ekomi.com';
$mail->Subject  = 'ERROR ON '. EKOMI_API_ID;
$mail->Body     = 'ERROR ON '. EKOMI_API_ID.'<br>'.$errstr.'<br>'.serialize(debug_backtrace());
$mail->AddAddress( 'error-report@ekomi.de' );

if(!$mail->Send()) {
    write_log(500, 'ERROR: sending errormail failed', false, 'ERROR');
}

if ($exit) {
    exit('An error occured. See log for further information.');
}

}

function handle_mysql_error ($file, $line, $sql) {

handle_error ('MySQL Error in ' . $file .' on line ' . $line .': '.$sql . ' - ERR: ' . mysql_error(), true);

}


?>