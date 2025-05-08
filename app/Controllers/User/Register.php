<?php
declare(strict_types=1);

namespace App\Controllers\User;

use App\Services\UserService;
use support\Request;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Register extends Common
{
    /**
     * 注入验证依赖
     *
     * @Inject
     * @var UserService
     */
    protected UserService $userService;

    /**
     * 用户注册
     *
     * @param Request $request
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function register(Request $request): Response
    {
        if ($request->isAjax()) {
            $this->userService->register();
        }

        return view('user/register');
    }
}