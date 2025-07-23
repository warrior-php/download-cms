<?php
declare(strict_types=1);

namespace App\Controller\Manages;

use App\Controller\Common;

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
}