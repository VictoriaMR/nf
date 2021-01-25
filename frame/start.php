<?php
//框架文件加载
require ROOT_PATH.'frame/app.php';
require ROOT_PATH.'frame/router.php';
require ROOT_PATH.'frame/container.php';
require ROOT_PATH.'frame/helper.php';
require ROOT_PATH.'frame/env.php';
if (is_file(ROOT_PATH . 'vendor/autoload.php')) {
	require ROOT_PATH . 'vendor/autoload.php';
}
if (isCli()) {
	App::init();
} else {
	App::run()->send();
}