<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\AdminModel;
use App\Utils\Encryptor;
use Exception;
use support\exception\BusinessException;
use support\Log;
use support\Redis;

class AdminService
{
    /**
     * @var string 会话加密密钥
     */
    public string $sessionKey;

    /**
     * 最大登录尝试次数
     *
     * @var int
     */
    private int $maxAttempts = 3;

    /**
     * 登录锁定时间（秒）
     *
     * @var int
     */
    private int $blockTime = 1800;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->sessionKey = uuid(5, false, request()->host() . 'admin_session_key');
    }

    /**
     * 管理员登录
     *
     * @param array $params
     *
     * @return void
     * @throws Exception
     */
    public function login(array $params): void
    {
        $ip = request()->getRealIp();
        $attemptsKey = 'login_attempts:' . $ip;
        // 检查是否被封锁
        if (((int)Redis::get($attemptsKey)) >= $this->maxAttempts) {
            throw new BusinessException(trans('admin.account.login.key010'));
        }
        // 校验图形验证码
        if (strtolower($params['captcha']) !== session('login-captcha')) {
            throw new BusinessException(trans('admin.account.login.key011'));
        }
        // 查找管理员
        $admin = AdminModel::where('email', $params['email'])->first();
        // 验证账号和密码
        if (!$admin || !password_verify($params['password'], $admin->password)) {
            $attempts = Redis::incr($attemptsKey);
            Redis::expire($attemptsKey, $this->blockTime);
            Log::warning(trans('admin.account.login.key012'), ['email' => $params['email'], 'ip' => $ip, 'attempts' => $attempts]);
            throw new BusinessException(trans('admin.account.login.key013'));
        }
        $admin->login_at = date('Y-m-d H:i:s');
        $admin->login_ip = $ip;
        $admin->save();
        // 加密用户数据并存储到会话中
        $enAdmin = Encryptor::encryptDecrypt(json_encode($admin), $this->sessionKey);
        session()->set('admin', $enAdmin);
        // 重置错误尝试次数
        Redis::del($attemptsKey);
    }

}