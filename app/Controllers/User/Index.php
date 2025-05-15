<?php
declare(strict_types=1);

namespace App\Controllers\User;

use App\Controllers\Common;
use App\Models\UserModel;
use support\Request;
use support\Response;

class Index extends Common
{
    /**
     * @return string
     */
    public function index(): string
    {
        return 'user index';
    }

    /**
     * 判断email是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    public function hasEmail(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasEmail($data['email'])) {
            return json(['error' => trans("This email address has been registered")]);
        }

        return json(['ok' => trans("Email can be registered")]);
    }

    /**
     * 判断username是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    public function hasUsername(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasUsername($data['username'])) {
            return json(['error' => trans("This username is already taken")]);
        }

        return json(['ok' => trans("Username can be registered")]);
    }
}