<?php
function dd($data = '') 
{
	print_r($data);
    exit();
}
function vv($data = '') 
{
    var_dump($data);
    exit();
}
function make($name)
{
	return \App::make($name);
}
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
function isMobile($mobile = '')
{
    if ($mobile != '') {
        return preg_match('/^1[34578]\d{9}$/', $mobile);
    }
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap')) {
        return true;
    }
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), 'VND.WAP.WML')) {
        return true;
    }
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
        return true;
    }
    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }
    return false;
}
function config($name = '') 
{
    if (empty($name)) return $GLOBALS;
    return $GLOBALS[$name] ?? [];
}
function dbconfig($db = 'default')
{
    return config('database')[$db] ?? [];
}
function env($name = '', $replace = '')
{
    if (empty($name)) return config('ENV');
    return config('ENV')[$name] ?? $replace;
}
function redirect($url)
{
    header('Location:'.$url);
    exit();
}
function isCli()
{
    return stripos(php_sapi_name(), 'cli') !== false;
}
function assign($name, $value = null)
{
    return \frame\View::getInstance()->assign($name, $value);
}
function view($template = '')
{
    return;
    return \frame\View::getInstance()->display($template);
}
function url($url = '', $param = []) 
{
    return \Router::buildUrl($url, $param);
}
function staticUrl($url, $type = '')
{
    if ($type == '') {
        return APP_DOMAIN . $url;
    } else {
        return APP_DOMAIN . $type . DS . $url . '.' . $type;
    }
}
function ipost($name = '', $default = null) 
{
    if (empty($name)) return $_POST;
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    return $default;
}
function iget($name = '', $default = null) 
{
    if (empty($name)) return $_GET;
    if (isset($_GET[$name])) {
        return  $_GET[$name];
    }
    return $default;
}
function input()
{
    return array_merge($_GET, $_POST);
}
function isJson($string) 
{ 
    if (is_array($string)) return false;
    $string = json_decode($string, true); 
    return json_last_error() == JSON_ERROR_NONE ? $string : false;
}
function redis($db = 0) 
{
    return \frame\Redis::getInstance($db);
}
function siteUrl($url = '')
{
    return APP_DOMAIN.$url;
}
function mediaUrl($url = '', $type='')
{
    if (strpos($url, 'http') === false && strpos($url, 'https') === false) {
        return APP_DOMAIN.'file_center'.DS.$url;
    }
    return $url;
}
function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        return $_SERVER['REMOTE_ADDR'];
    }
    return '';
}