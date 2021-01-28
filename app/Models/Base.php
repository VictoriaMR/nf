<?php

namespace App\Models;

class Base
{
    protected $_instance;
    protected $_connect;
    protected $_table;
    protected $_primaryKey;

    protected function getInstance()
    {
        if (is_null($this->_instance)) {
            $this->_instance = new \frame\Query($this->_connect, $this->_table);
        }
        return $this->_instance;
    }

    public function loadData($id)
    {   
        $id = (int) $id;
        if (empty($id)) return [];
        return $this->getInstance()->where($this->_primaryKey, $id)->find();
    }

    public function updateDataById($id, $data)
    {
        $id = (int) $id;
        if (empty($id)) return false;
        return $this->getInstance()->where($this->_primaryKey, $id)->update($data);
    }

    public function deleteById($id)
    {
        $id = (int) $id;
        if (empty($id)) return false;
        return $this->getInstance()->where($this->_primaryKey, $id)->delete();
    }

    public function insertGetId(array $data)
    {
        if (empty($data)) return false;
        return $this->getInstance()->insertGetId($data);
    }

    public function insert(array $data)
    {
        if (empty($data)) return false;
        return $this->getInstance()->insert($data);
    }

    public function getCount(array $where = []) 
    {
        return $this->getInstance()->where($where)->count();
    }

    public function getInfoByWhere(array $where = [], array $fields = [])
    {
        return $this->getInstance()->where($where)->field($fields)->find();
    }

    public function getPaginationList($total = 0, $list = [], $page = 1, $pagesize = 10)
    {
        return [
            'total' => $total,
            'pagesize' => $pagesize,
            'page' => $page,
            'page_total' => ceil($total / $pagesize),
            'list' => $list,
        ];
    }
}