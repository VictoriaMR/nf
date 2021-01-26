<?php

namespace frame;

class View 
{
    private static $_instance = null;

    protected static $data = [];

    public static function getInstance() 
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function display($template = '')
    {
        $template = $this->getTemplate($template);
        if (is_file($template)) {
            extract(self::$data);
            include($template);
        } else {
            exit($template.' was not exist!');
        }
    }

    private function getTemplate($template) 
    {
        $matchPath = '';
        if (env('APP_VIEW_MATCH')) {
            $matchPath = (isMobile() ? 'Mobile' : 'Computer') . DS;
        }
        $_route = \Router::$_route;
        if (empty($template)) {
            $template = implode(DS, $_route);
        } else {
            if (false === strrpos($template, DS)) {
                $_route['func'] = $template;
                $template = implode(DS, $_route);
            } else {
                $template = $_route['class'] . DS . $template;
            }
        }
        return ROOT_PATH . 'view' . DS . $matchPath . $template . '.php';
    }

    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            self::$data = array_merge(self::$data, $name);
        } else {
            self::$data[$name] = $value;
        }
        return $this;
    }

    public static function load($template = '')
    {
        return self::getInstance()->display($template);
    }
}