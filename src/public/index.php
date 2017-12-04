<?php
if (phpversion() >= '5.3') {
    define('APPLICATION_PATH', dirname(__DIR__));
} else {
    define('APPLICATION_PATH', dirname(dirname(__FILE__)));
}

define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */
require_once APP_PATH .'/vendor/autoload.php';

$app  = new Yaf_Application(APP_PATH . "/application/conf/application.ini");
$app->bootstrap()->run();