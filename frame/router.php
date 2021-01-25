<?php
final class Router
{
	public static $_route = []; //路由

	public static function analyze()
	{
		$pathInfo = trim($_SERVER['REQUEST_URI'], DS);
		if (empty($pathInfo)) {
			self::$_route = [
				'class' => 'Home',
				'path' => 'Index',
				'func' => 'index',
			];
			return true;
		} else {
			$pathInfo = explode(DS, explode('?', $pathInfo)[0]);
			if (empty($pathInfo[0])) {
				self::$_route = [
					'class' => 'Home',
					'path' => 'Index',
					'func' => 'index',
				];
				return true;
			}
	        self::$_route['class'] = ucfirst(array_shift($pathInfo));
			if (count($pathInfo) > 1) {
		        self::$_route['func'] = lcfirst(array_pop($pathInfo));
		       	self::$_route['path'] = ucfirst($pathInfo);
			} else {
		        self::$_route['path'] = 'Index';
		        self::$_route['func'] = 'index';
			}
			return true;
		}
	}
}