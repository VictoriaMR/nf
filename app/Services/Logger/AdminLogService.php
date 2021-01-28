<?php 
namespace App\Services\Logger;

use App\Services\Base as BaseService;
use App\Models\Logger\AdminLogger;

class AdminLogService extends BaseService
{	
	protected static $constantMap = [
        'base' => AdminLogger::class,
    ];

	public function __construct(AdminLogger $model)
    {
        $this->baseModel =  $model;
    }

    public function addLog(array $data)
    {
    	if (empty($data)) return false;
    	$temp = [
    		'ip' => getIp(),
    		'agent' => $_SERVER['HTTP_USER_AGENT'],
    		'create_at' => $this->getTime(),
    		'path' => implode(DS, \Router::$_route),
    		'param' => json_encode(array_filter(input()), JSON_UNESCAPED_UNICODE),
    	];
    	$data = array_merge($temp, $data);
    	return $this->baseModel->insert($data);
    }
}