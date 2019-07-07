<?php
/*******************
*                  *
*     session      *
*                  *
********************/
//session_start(); //guess has to be executed in each file manually

/*******************
*                  *
*     include      *
*                  *
********************/
// own
require_once(__DIR__ . "/usefull_functions.php");
require_once(__DIR__ . "/secrets.php");

// other
/*
// directly included in the mail related files because its not working like this
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(__DIR__ . "/other/PHPMailer/Exception.php");
require_once(__DIR__ . "/other/PHPMailer/PHPMailer.php");
require_once(__DIR__ . "/other/PHPMailer/SMTP.php");
*/

/*******************
*                  *
*   PHP debugging  *
*                  *
********************/
ob_start();
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


/*******************
*                  *
*      TIME        *
*                  *
********************/
date_default_timezone_set('Europe/Berlin');

/*******************
*                  *
* Global constants *
*                  *
********************/

//depending on server
const TEST_VPN_DIR = "/var/www/TestVPN-scripts/";
const DATABASE_PATH_RAW = TEST_VPN_DIR . "users.db";
const DATABASE_PATH = "sqlite:" . DATABASE_PATH_RAW;
const ABSOLUTE_DATABASE_PATH = DATABASE_PATH; //handle all absolut for simplicity

const CERT_PATH = TEST_VPN_DIR . "certs/";
const DOMAIN_NAME = "TestVPN.tk";

//configs
const IS_ELASTIC = true;
const IS_RENAME = true;
const MAIL_ADDR = "info.testvpn@gmail.com";
?>
