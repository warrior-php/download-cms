<?php
declare(strict_types=1);

namespace App\Controller\Manage;

use App\Controller\Common;
use support\Request;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Index extends Common
{
    /**
     * 管理后台
     *
     * @return string
     */
    public function index(): string
    {
        return '管理后台首页';
    }

    /**
     * 管理员登录
     * 每个ip60秒最多10个请求
     *
     * @param Request $request
     *
     * @return Response
     */
    #[RateLimiter(limit: 10, ttl: 60)]
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $data = request()->post();
            $this->validate('Manage', $data, 'login');
        }
        return view('manage/login');
    }
}