<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Common;
use support\Response;

class Index extends Common
{
    /**
     * 管理后台首页
     *
     * @return Response
     */
    public function index(): Response
    {
        return view('admin/index');
    }
}