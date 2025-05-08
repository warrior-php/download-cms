<?php
declare(strict_types=1);

namespace App\Controllers\Authorize;

use Exception;
use support\Request;
use support\Response;
use Webman\RateLimiter\Annotation\RateLimiter;

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
    #[RateLimiter(limit: 3, ttl: 1)]
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $this->authorizeValidation->validate();
        }

        return view('authorize/login');
    }

    /**
     * 登出
     *
     * @return string
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function logout(): string
    {
        return '退出';
    }

    /**
     * 找回密码
     *
     * @return string
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function forget(): string
    {
        return '退出';
    }

}