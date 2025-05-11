<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;
use App\Rules\UserRule;

class UserService
{
    /**
     * 注入验证依赖
     *
     * @Inject
     * @var UserRule
     */
    protected UserRule $userRule;

    /**
     * 用户注册
     *
     * @return void
     */
    public function register(): void
    {
        $data = request()->post();
        $this->userRule->validate($data);
        UserModel::createUser($data);
    }
}