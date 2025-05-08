<?php
declare(strict_types=1);

use Webman\Session\FileSessionHandler;
use Webman\Session\RedisSessionHandler;
use Webman\Session\RedisClusterSessionHandler;

return [

    'type'    => 'redis', // or redis or redis_cluster
    'handler' => RedisSessionHandler::class,
    'config'  => [
        'file' => [
            'save_path' => runtime_path() . '/sessions',
        ],

        'redis' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'auth'     => '',
            'timeout'  => 2,
            'database' => '',
            'prefix'   => 'redis_session_',
        ],

        'redis_cluster' => [
            'host'    => ['127.0.0.1:7000', '127.0.0.1:7001', '127.0.0.1:7001'],
            'timeout' => 2,
            'auth'    => '',
            'prefix'  => 'redis_session_',
        ]
    ],

    'session_name'          => 'FJx09_hTc',
    'auto_update_timestamp' => false,
    'lifetime'              => 7 * 24 * 60 * 60,
    'cookie_lifetime'       => 365 * 24 * 60 * 60,
    'cookie_path'           => '/',
    'domain'                => '',
    'http_only'             => true,
    'secure'                => false,
    'same_site'             => '',
    'gc_probability'        => [1, 1000]
];
