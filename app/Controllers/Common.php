<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use support\Request;
use Warrior\RateLimiter\Annotation\RateLimiter;
use support\Response;

class Common
{
    /**
     * 判断email是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function hasEmail(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasEmail($data['email'])) {
            return json(['error' => trans("This email address has been registered")]);
        }

        return json(['ok' => '邮箱可以注册']);
    }

    /**
     * 判断username是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function hasUsername(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasUsername($data['username'])) {
            return json(['error' => trans("This username is already taken")]);
        }

        return json(['ok' => '用户名可以注册']);
    }

    public function emailVerify()
    {
        dump(111);
    }
}