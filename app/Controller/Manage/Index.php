<?php
declare(strict_types=1);

namespace App\Controller\Manage;

use App\Controller\Common;
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
     * @return Response
     */
    public function login(): Response
    {
        return view('manage/login');
    }
}