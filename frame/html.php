<?php 

namespace frame;

class Html
{
	public static $_CSS = [];
	public static $_JS = [];

	public static function addCss($name = '')
	{
		$matchPath = '';
        if (env('APP_VIEW_MATCH')) {
            $matchPath = (isMobile() ? 'Mobile' : 'Computer') . DS;
        }
        $_route = \Router::$_route;
        if (empty($name)) {
            $name = implode(DS, $_route);
        } else {
            if (false === strrpos($name, DS)) {
                $_route['func'] = $name;
                $name = implode(DS, $_route);
            } else {
                $name = $_route['class'] . DS . $name;
            }
        }
        self::$_CSS[] = env('APP_DOMAIN') . 'css' . DS . $matchPath . $name . '.css';
        return true;
	}

	public static function addJs($name = '', $public = false)
	{
		$matchPath = '';
        if (env('APP_VIEW_MATCH')) {
            $matchPath = (isMobile() ? 'Mobile' : 'Computer') . DS;
        }
        $_route = \Router::$_route;
        if (empty($name)) {
            $name = implode(DS, $_route);
        } else {
            if (false === strrpos($name, DS)) {
                $_route['func'] = $name;
                $name = implode(DS, $_route);
            } else {
                $name = $_route['class'] . DS . $name;
            }
        }
        self::$_JS[] = env('APP_DOMAIN') . 'js' . DS . $matchPath . $name . '.js';
        return true;
	}

	public static function getCss()
	{
		if (empty(self::$_CSS)) return [];
		return array_unique(self::$_CSS);
	}

	public static function getJs()
	{
		if (empty(self::$_JS)) return [];
		return array_unique(self::$_JS);
	}
}