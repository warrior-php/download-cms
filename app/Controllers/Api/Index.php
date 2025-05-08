<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use Warrior\RateLimiter\Annotation\RateLimiter;

class Index
{
    /**
     * @return string
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): string
    {
        return 'ApiTraits 接口';
    }
}