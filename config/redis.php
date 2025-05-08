<?php
declare(strict_types=1);

return [
    // 默认 Redis 配置
    'default' => [
        'host'     => getenv('REDIS_HOST'),
        'password' => getenv('REDIS_PASSWORD'),
        'port'     => getenv('REDIS_PORT'),
        'database' => getenv('REDIS_DATABASE'),

        'pool' => [
            'max_connections'    => 5,
            'min_connections'    => 1,
            'wait_timeout'       => 3,
            'idle_timeout'       => 60,
            'heartbeat_interval' => 50,
        ],
    ],
];