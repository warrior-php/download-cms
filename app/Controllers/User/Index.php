<?php
declare(strict_types=1);

namespace App\Controllers\User;

use Webman\RateLimiter\Annotation\RateLimiter;

class Index extends Common
{
    /**
     * @return string
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): string
    {
        return 'user index';
    }
}