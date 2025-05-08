<?php
declare(strict_types=1);

namespace App\Controllers\Manages;

use Warrior\RateLimiter\Annotation\RateLimiter;

class Index extends Common
{
    /**
     * 管理后台
     *
     * @return string
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): string
    {
        return '管理后台首页';
    }
}