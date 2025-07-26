<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Common;
use App\Service\AdminService;
use DI\Attribute\Inject;
use Exception;
use support\Request;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Account extends Common
{
    /**
     * 注入账户服务
     *
     * @var AdminService
     */
    #[Inject]
    protected AdminService $adminService;

    /**
     * 管理员登录
     * 每个ip60秒最多10个请求
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    #[RateLimiter(limit: 10, ttl: 60)]
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $params = request()->post();
            // 验证数据
            $this->validate('Admin', $params, 'login');
            $this->adminService->login($params);
            return result(302, trans('admin.account.login.key014'), ['url' => url('admin.index')]);
        }
        return view('admin/account/login');
    }
}