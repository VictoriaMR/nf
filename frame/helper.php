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
function isMobile()
{
    if (!empty($_SESSION['site_type'])) return $_SESSION['site_type'];
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap'))
        return true;
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), 'VND.WAP.WML')) 
        return true;
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))
        return true;
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