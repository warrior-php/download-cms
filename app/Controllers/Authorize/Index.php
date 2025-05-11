<?php
declare(strict_types=1);

namespace App\Controllers\Authorize;

use Exception;
use support\Request;
use support\Response;

class Index extends Common
{
    /**
     * 登录
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $this->authorizeRule->validate();
        }

        return view('authorize/login');
    }

    /**
     * 登出
     *
     * @return string
     */
    public function logout(): string
    {
        return '退出';
    }

    /**
     * 找回密码
     *
     * @return string
     */
    public function forget(): string
    {
        return '退出';
    }

}