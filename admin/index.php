<?php
define('APP_MEMORY_START', memory_get_usage());
define('APP_TIME_START', microtime(true));
define('DS', '/');
define('ROOT_PATH', strtr(realpath(dirname(__FILE__).'/../').'/', '\\', '/'));
define('APP_TEMPLATE_TYPE', 'Admin');
define('APP_DOMAIN', 'http://localhost:8083/');
require ROOT_PATH.'frame/start.php';