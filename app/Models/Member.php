<?php

namespace App\Models;

use App\Models\Base as BaseModel;

class Member extends BaseModel
{
    //表名
    protected $_table = 'member';
    //主键
    protected $_primaryKey = 'mem_id';

    public function isExistUserByMobile($mobile)
    {
        if (empty($mobile)) return false;
        return $this->getCount(['mobile' => $mobile])['count'] ?? 0;
    }

    public function getInfoByMobile($mobile)
    {
    	return $this->getInfoByWhere(['mobile' => $mobile])->find();
    }

    public function getInfo($userId)
    {
        return $this->loadData($userId);
    }
}