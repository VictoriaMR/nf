<?php

namespace App\Services;

/**
 * 业务模型基类.
 */
class Base
{
    /**
     * 关联数据Model.
     *
     * var App\Model\Base
     */
    protected $baseModel = null;

    /**
     * 常量映射关系表.
     */
    protected static $constantMap = [];

    /**
     * 通过主键获取资料.
     *
     * @param mix $id 主键值
     *
     * @return array
     */
    public function loadData($id)
    {
        return $this->baseModel->loadData($id);
    }

    /**
     * 新增数据.
     *
     * @param array $data 新增数据
     */
    public function insert($data)
    {
        return $this->baseModel->insert($data);
    }

    /**
     * 新增数据.
     *
     * @param array $data 新增数据
     */
    public function insertGetId($data)
    {
        return $this->baseModel->insertGetId($data);
    }

    /**
     * 通过主键更新数据.
     *
     * @param mix   $id
     * @param array $data
     *
     * @return bool
     */
    public function updateDataById($id, $data)
    {
        return $this->baseModel->updateDataById($id, $data);
    }

    /**
     * @method 是否存在数据
     * @author Victoria
     * @date   2020-04-25
     * @return boolean  
     */
    public function isExistData($proId)
    {
        return $this->baseModel->isExistData($proId);
    }

    /**
     * 通过filter更新数据.
     *
     * @param array $filter 更新条件 
     * @param array $data   更新数据
     */
    public function updateDataByFilter($filter, $data)
    {
        return $this->baseModel->updateDataByFilter($filter, $data);
    }

    /**
     * 通过主键进行删除.
     *
     * @param $id 主键值
     */
    public function deleteById($id)
    {
        return $this->baseModel->deleteById($id);
    }

    /**
     * 获取常量继承方法
     * @author   Mingrong
     * @DateTime 2020-01-10
     * @param    [type]     $const [description]
     * @param    string     $model [description]
     * @return   
     */
    public static function constant($const, $model = 'base')
    {
        $namespace = 'static';
        if (isset(static::$constantMap[$model])) {
            $namespace = static::$constantMap[$model];
        }
        return constant($namespace.'::'.$const);
    }

    /**
     * @method 返回页码总数格式
     * @author Victoria
     * @date   2020-04-13
     * @return array
     */
    public function getPaginationList($total, $list, $page, $pagesize)
    {
        return $this->baseModel->getPaginationList($total, $list, $page, $pagesize);
    }

    /**
     * @method 获取随机数
     * @author Victoria
     * @date   2020-01-10
     * @return string salt
     */
    public function getSalt($len = 4)
    {
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $charsLen = strlen($chars) - 1;
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= $chars[rand(0, $charsLen)];
        }
        return $str;
    }

    /**
     * @method 获取密码与随机值的组合
     * @author Victoria
     * @date   2020-01-10
     * @return string password
     */
    public function getPasswd($password, $salt)
    {
        $passwordArr = str_split($password);
        $saltArr = str_split($salt);
        $countpwd = count($passwordArr);
        $countSalt = count($saltArr);

        $password = '';
        if ($countSalt > $countpwd) {
            foreach ($saltArr as $key => $value) {
                $password .= $passwordArr[$key] ?? '' . $value;
            }
        } else {
            $i = 0;
            $sign = floor($countpwd / $countSalt);
            foreach ($passwordArr as $key => $value) {
                $password .= $value;
                if ($key % $sign == 0) {
                    if (empty($saltArr[$i])) $i = 0;

                    $password .= $saltArr[$i];
                    $i ++;
                }
            }
        }

        return $password;
    }
}