<?php
ini_set('error_reporting', E_STRICT);
require_once('app/core/Constants.php');
require_once('vendor/autoload.php');

new ScurityCheck();

new Master();
ob_flush();