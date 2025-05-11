<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;

class UserService
{
    /**
     * 用户注册
     *
     * @param array|null $data 要验证的数据，默认取 request()->post()
     *
     * @return void
     */
    public function register(?array $data = null): void
    {
        $data = $data ?? request()->post();
        UserModel::checkUserUniqueness([
            'email'    => $data['email'],
            'username' => $data['username']
        ]);

        UserModel::createUser($data);
    }
}