<?php

namespace App\Middleware;

use frame\Session;

class VerifyToken
{
    protected static $except = [
        'Admin/Login/index' => true,
        'Admin/Login/loginCode' => true,
    ];

    public static function handle($request)
    {
        if (self::inExceptArray(implode(DS, $request))) {
            return true;
        }
        switch ($request['class']) {
            case 'Admin':
                $loginKey = 'admin_mem_id';
                break;
        }
        //检查登录状态
        if (!empty($loginKey) && empty(Session::get($loginKey))) {
            redirect(url('login'));
        }
        return true;
    }

    private static function inExceptArray($route)
    {
        return self::$except[$route] ?? false;
    }
}
