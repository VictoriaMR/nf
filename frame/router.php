<?php
final class Router
{
	public static $_route = []; //路由

	public static function analyze()
	{
		$pathInfo = trim($_SERVER['REQUEST_URI'], DS);
		if (empty($pathInfo)) {
			self::$_route = [
				'class' => APP_TEMPLATE_TYPE,
				'path' => 'Index',
				'func' => 'index',
			];
			return true;
		} else {
			$pathInfo = explode(DS, explode('?', $pathInfo)[0]);
			if (empty($pathInfo[0])) {
				self::$_route = [
					'class' => APP_TEMPLATE_TYPE,
					'path' => 'Index',
					'func' => 'index',
				];
				return true;
			}
	        self::$_route['class'] = ucfirst(array_shift($pathInfo));
	        switch (count($pathInfo)) {
	        	case 0:
	        		self::$_route['path'] = 'Index';
		        	self::$_route['func'] = 'index';
	        		break;
	        	case 1:
	        		self::$_route['path'] = lcfirst(implode(DS, $pathInfo));
		        	self::$_route['func'] = 'index';
	        		break;
	        	default:
	        		$func = array_pop($pathInfo);
	        		self::$_route['path'] = ucfirst(implode(DS, $pathInfo));
	        		self::$_route['func'] = lcfirst($func);
	        		break;
	        }
			return true;
		}
	}

	public static function buildUrl($url = '', $param = [])
	{
		if (empty($url)) {
			$url = implode(DS, self::$_route);
		} else {
			$url = self::$_route['class'] . DS . $url;
		}
		if (!empty($param)) {
			$url .= '?' . http_build_query($param);
		}
		return APP_DOMAIN.$url;
	}
}