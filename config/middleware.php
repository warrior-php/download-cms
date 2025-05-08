<?php
declare(strict_types=1);

use Warrior\RateLimiter\Limiter;

return [
    // 全局中间件
    '' => [
        App\Middleware\InitApp::class
    ],

    '@' => [
        Limiter::class
    ],
];