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
        $attemptsKey = 'admin_login:attempts:' . $ip;
        // 登录失败次数检查
        if ((int)Redis::get($attemptsKey) >= $this->maxAttempts) {
            throw new BusinessException(trans('admin.account.login.key010')); // 封锁中
        }
        // 图形验证码校验
        $captcha = mb_strtolower($params['captcha'] ?? '');
        $sessionCaptcha = mb_strtolower(session('login-captcha') ?? '');
        session()->delete('login-captcha'); // 验证后立即删除
        if ($captcha !== $sessionCaptcha) {
            throw new BusinessException(trans('admin.account.login.key011')); // 验证码错误
        }
        // 查找管理员
        $admin = AdminModel::where('email', $params['email'])->first();
        // 密码校验失败：记录尝试次数
        if (!$admin || !password_verify($params['password'], $admin->password)) {
            $attempts = Redis::incr($attemptsKey);
            Redis::expire($attemptsKey, $this->blockTime);
            Log::warning(
                trans('admin.account.login.key012'),
                ['email' => $params['email'], 'ip' => $ip, 'attempts' => $attempts]
            );
            throw new BusinessException(trans('admin.account.login.key013')); // 账号或密码错误
        }
        // 更新登录记录
        $admin->login_at = date('Y-m-d H:i:s');
        $admin->login_ip = $ip;
        $admin->save();
        // 仅保存必要字段到 session
        $sessionData = [
            'id'       => $admin->id,
            'email'    => $admin->email,
            'name'     => $admin->name,
            'login_at' => $admin->login_at,
        ];
        $enAdmin = Encryptor::encryptDecrypt(json_encode($sessionData), $this->sessionKey);
        session()->set('admin', $enAdmin);
        // 登录成功：重置失败计数
        Redis::del($attemptsKey);
    }

}