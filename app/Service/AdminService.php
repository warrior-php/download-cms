<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\AdminModel;
use support\exception\BusinessException;
use support\Redis;

class AdminService
{
    /**
     * @var int 最大登录尝试次数
     */
    private int $maxAttempts = 3;

    /**
     * @var int 登录锁定时间（秒）
     */
    private int $blockTime = 1800;

    public function login($params)
    {
        $params['ip'] = request()->getRealIp();
        $attemptsKey = 'login_attempts:' . $params['ip'];
        if ($this->isIpBlocked($attemptsKey)) {
            throw new BusinessException(message: trans('错误次数过多，稍后再试'));
        }
        $admin = AdminModel::where('email', $params['email'])->first();
        if (!$admin) {
            throw new BusinessException(message: trans('帐户不存在'));
        }
    }

    /**
     * 检查IP是否被封锁
     *
     * @param string $attemptsKey
     *
     * @return bool
     */
    private function isIpBlocked(string $attemptsKey): bool
    {
        $attempts = (int)Redis::get($attemptsKey);
        return $attempts >= $this->maxAttempts;
    }
}