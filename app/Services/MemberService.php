<?php 

namespace app\Services;

use App\Services\Base as BaseService;
use App\Models\Member;

/**
 * 	用户公共类
 */
class MemberService extends BaseService
{	
	const INFO_CACHE_TIMEOUT = 3600 *24;
	const TYPE_MEMBER_CUSTOMER = 1;
	const TYPE_MEMBER_PROXY = 3;
	const TYPE_MEMBER_ADMIN = 5;

	public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

	/**
	 * @method 创建用户
	 * @author Victoria
	 * @date   2020-01-12
	 * @return boolean
	 */
	public function create($data)
	{
		if (empty($data['password'])) return false;
		$data['salt'] = $this->getSalt();
		$data['password'] = password_hash($this->getPasswd($data['password'], $data['salt']), PASSWORD_DEFAULT);

		return $this->baseModel->insertGetId($data);
	}

	public function updateById($memId, $data)
	{
		if (empty($memId) || empty($data)) return false;

		if (!empty($data['password'])) {
			$data['salt'] = $this->getSalt();
			$data['password'] = password_hash($this->getPasswd($data['password'], $data['salt']), PASSWORD_DEFAULT);
		}

		return $this->baseModel->updateDataById($memId, $data);
	}

	/**
	 * @method 登陆
	 * @author Victoria
	 * @date   2020-01-11
	 * @param  string     $name    	名称或者手机号码
	 * @param  string     $password 密码
	 * @return array
	 */
	public function login($mobile, $password, $type = 1)
	{
		if (empty($mobile) || empty($password)) return false;

		$info = $this->getInfoByMobile($mobile);
		dd($info);

		if (empty($info)) return false;

		if (!$info['status']) return false;

		$password = $this->getPasswd($password, $info['salt']);

		if ($this->checkPassword($password, $info['password'])) {
			$data = [
				'mem_id' => $info['mem_id'],
				'name' => $info['name'],
				'mobile' => $info['mobile'],
				'nickname' => $info['nickname'],
				'avatar' => media($info['avatar'], 'avatar', ['female'=> ($info['gender'] ?? 0) == 0 ? true : false]),
				'salt' => $info['salt'],
			];

			if ($isAdmin) {
				if (!empty($info['color_id'])) {
					$colorService = \App::make('App\Services\Admin\ColorService');
					$colorInfo = $colorService->loadData($info['color_id']);
				}
				$data['is_super'] = $info['is_super'];
				$data['color_name'] = $colorInfo['color_name'] ?? '';
				$data['color_value'] = $colorInfo['color_value'] ?? '';
			}
			return Session::set($isAdmin ? 'admin' : 'home', $data);
		}
		return false;
	}

	/**
	 * @method 检查两者密码
	 * @author Victoria
	 * @date   2020-03-22
	 * @param  string        $inPassword     输入密码
	 * @param  string        $sourcePassword 源密码
	 * @return boolean
	 */
	public function checkPassword($inPassword = '', $sourcePassword = '')
	{
		return password_verify($inPassword, $sourcePassword);
	}

	/**
	 * @method 根据手机号码获取信息
	 * @author Victoria
	 * @date   2020-01-11
	 * @return array
	 */
	public function getInfoByMobile($mobile)
	{
		if (empty($mobile)) return [];
		return $this->baseModel->getInfoByMobile($mobile);
	}

	/**
	 * @method 检查用户存在
	 * @author Victoria
	 * @date   2020-01-11
	 * @return boolean
	 */
	public function isExistUserByMobile($mobile) 
	{
		return $this->baseModel->isExistUserByMobile($mobile);
	}

	/**
     * 通过ID获取用户信息
     * 
     * @param string $userId
     * @return array 用户信息
     */
    public function getInfo($userId)
    {
        return $this->baseModel->getInfo($userId);
    }

    /**
     * @method 获取缓存数据
     * @author Victoria
     * @date   2020-01-12
     * @param  integer      $userId 
     * @return array
     */
    public function getInfoCache($userId)
    {
        $cacheKey = $this->getInfoCacheKey($userId);
        $info = Redis()->get($cacheKey);
        if (!empty($info)) {
            return $info;
        } else {
            $info = $this->getInfo($userId);
            if (!empty($info)) {
            	if (empty($info['avatar'])) {
            		$info['avatar'] = $this->getDefaultAvatar($userId);
            	} else {
            		$info['avatar'] = media($info['avatar']);
            	}
            }
            Redis()->set($cacheKey, $info, self::INFO_CACHE_TIMEOUT);

            return $info;
        }
    }

    public function getDefaultAvatar($userId, $male = false)
    {
    	if ($male) {
    		return siteUrl('image/common/male.jpg');
    	} else {
    		return siteUrl('image/common/female.jpg');
    	}
    }

    /**
     * @method 缓存key
     * @author Victoria
     * @date   2020-01-12
     * @param  string      $userId 
     * @return string cachekey
     */
    public function getInfoCacheKey($userId)
    {
        return 'MEMBER_INFO_CACHE_' . $userId;
    }

    /**
     * @method 清除缓存
     * @author Victoria
     * @date   2020-01-12
     * @param  integer      $userId 
     * @return boolean
     */
    public function clearCache($userId)
    {
        $cacheKey = $this->getInfoCacheKey($userId);
        return Cache::foget($cacheKey);
    }
}