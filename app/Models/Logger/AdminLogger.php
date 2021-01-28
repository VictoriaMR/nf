<?php

namespace App\Models\Logger;

use App\Models\Base as BaseModel;

class AdminLogger extends BaseModel
{
    //表名
    protected $_table = 'admin_logger';
    //主键
    protected $_primaryKey = 'log_id';
    //库
    protected $_connect = 'static';

    const TYPE_ID_LOGIN = 0; //登录登出
}