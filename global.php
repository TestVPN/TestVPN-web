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
require_once(__DIR__ . "/usefull_functions.php");
require_once(__DIR__ . "/secrets.php");

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
const DATABASE_PATH_RAW = "/var/www/TestVPN/users.db";
const DATABASE_PATH = "sqlite:" . DATABASE_PATH_RAW;
const ABSOLUTE_DATABASE_PATH = DATABASE_PATH; //handle all absolut for simplicity

const CERT_PATH = "/var/www/TestVPN/certs/";

//configs
const SAMPLE_CONFIG = 10;

?>

