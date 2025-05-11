<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;

class UserService
{
    /**
     * 用户注册
     *
     * @param $data
     *
     * @return void
     */
    public function register($data): void
    {
        UserModel::createUser($data);
    }
}