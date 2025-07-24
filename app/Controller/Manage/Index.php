<?php
declare(strict_types=1);

namespace App\Controller\Manage;

use App\Controller\Common;
use support\Request;
use support\Response;

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
     *
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $data = request()->post();
            $this->validate('Manage', $data, 'login');
        }
        return view('manage/login');
    }
}