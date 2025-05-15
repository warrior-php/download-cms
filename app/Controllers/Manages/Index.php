<?php
declare(strict_types=1);

namespace App\Controllers\Manages;

use App\Controllers\Common;

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