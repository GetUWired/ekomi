<?php
/**
 * MySQL Datenbankverbindung herstellen - Bestellstatus, bei dem die E-Mail verschickt werden soll - E-Mail Versand Einstellungen - SMTP Daten
 *
 * @package default
 */


/***************************
*  api settings            *
***************************/

// eKomi API ID
define( 'EKOMI_API_ID',                  '');

// eKomi API password
define( 'EKOMI_API_KEY',                 '');


/***************************
* log settings             *
***************************/

define('EKOMI_LOG_ENABLED',            '1');    // enable logging?
define('EKOMI_LOG_FILE',               './log/log.log');    // storage file

/***************************
* storage settings         *
***************************/

// where to store the data. values: 'db' or 'file'
define( 'EKOMI_STORAGE',                 'db');


/***************************
* database settings        *
***************************/

define( 'EKOMI_DB_SERVER',               'localhost' );
define( 'EKOMI_DB_USER',                 '' );
define( 'EKOMI_DB_PASS',                 '' );
define( 'EKOMI_DB_NAME',                 '' );
define( 'EKOMI_DB_TABLE',                EKOMI_API_ID . '_ekomi_customers' );


/***************************
* e-mail settings          *
***************************/

// sender address. Overrides setting in ekomi cutomer area if set.
define( 'EKOMI_MAIL_SENDER',             'reviews@feedback-ekomi.com' );

// smtp-mode. 0=use php's mail() || 1=use smtp
define( 'EKOMI_SMTP_MODE',               '0' );

//smtp-login details. only used when EKOMI_SMTP_MODE = 1
define( 'EKOMI_SMTP_SERVER',             '' );
define( 'EKOMI_SMTP_PORT',               '' );
define( 'EKOMI_SMTP_USER',               '' );
define( 'EKOMI_SMTP_PASS',               '' );


define( 'EKOMI_VERSION',                 '2.0.0' );

?>