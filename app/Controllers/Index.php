<?php
declare(strict_types=1);

namespace App\Controllers;

use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Index
{
    /**
     * 网站首页
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): Response
    {
        return view('index');
    }
}