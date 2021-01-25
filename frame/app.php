<?php
class App 
{
	private static $_instance = null;

    public static function instance() 
    {
    	if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

	public static function run() 
	{
		//初始化方法
		self::init();
        //注册异常处理
        Router::analyze();
		//解析路由
		return self::instance();
	}

    public function send()
    {
        //路由解析
        $info = Router::$_route;
        $class = 'App\\Controllers\\'.$info['class'].'\\'.$info['path'].'Controller';
        if (is_callable([self::autoload($class), $info['func']])) {
            call_user_func_array([self::autoload($class), $info['func']], []);
        }
        exit();
    }

	public static function init() 
	{
		spl_autoload_register([__CLASS__ , 'autoload']);
	}

	private static function autoload($abstract) 
    {
        $abstract = strtr($abstract, '/', '\\');
        //容器加载
        if (!empty(Container::$_building[$abstract])) {
            return Container::$_building[$abstract];
        }

        $file = ROOT_PATH.strtr($abstract, ['\\'=>'/', 'App\\'=>'app/']).'.php';
        if (is_file($file)) {
			require $file;
        } else {
			exit($abstract.' was not exist!');
        }

		return Container::getInstance()->autoload($abstract);
    }

    public static function make($abstract)
    {
    	return self::autoload($abstract);
    }
}