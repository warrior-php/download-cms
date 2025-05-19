<?php
declare(strict_types=1);

use App\Jobs\Monitor;
use support\Log;
use support\Request;

global $argv;

return [
    // 主 HTTP 服务配置
    'HttpService' => [
        'handler'     => Webman\App::class,         // 请求处理类（自定义继承处理器）
        'listen'      => 'http://0.0.0.0:8787',     // 监听地址与端口
        'count'       => 1,                         // 启动进程数，建议 cpu 核心数的倍数：cpu_count() * 4
        'user'        => '',                        // 指定运行用户（留空为当前用户）
        'group'       => '',                        // 指定运行用户组
        'reusePort'   => false,                     // 是否复用端口（仅 Linux 支持）
        'eventLoop'   => '',                        // 自定义事件循环类，默认空
        'context'     => [],                        // 自定义上下文参数
        'constructor' => [
            'requestClass' => Request::class,               // 请求类（用于替换默认 Request）
            'logger'       => Log::channel(),               // 日志实例
            'appPath'      => app_path(),                   // 应用目录
            'publicPath'   => public_path(),                // public 目录
        ]
    ],

    // 热重载配置（开发环境使用）
    'Monitor'     => [
        'handler'     => Monitor::class,                // 热重载进程类
        'reloadable'  => false,                         // 是否允许子进程自动重载
        'constructor' => [
            'monitorDir' => array_merge([
                app_path(),                                // app 目录
                config_path(),                             // 配置目录
                base_path() . '/process',                  // 自定义进程
                base_path() . '/support',                  // 框架支持类
                base_path() . '/resources',                // 静态资源
                base_path() . '/.env',                     // 环境变量
            ],
                glob(base_path() . '/plugin/*/app'),
                glob(base_path() . '/plugin/*/config'),
                glob(base_path() . '/plugin/*/api')),

            'monitorExtensions' => ['php', 'html', 'htm', 'env', 'twig'], // 监听的文件扩展名

            'options' => [
                'enable_file_monitor'   => !in_array('-d', $argv) && DIRECTORY_SEPARATOR === '/',   // 启用文件变更监听（Linux/macOS）
                'enable_memory_monitor' => DIRECTORY_SEPARATOR === '/',                                     // 启用内存监控（Linux/macOS）
            ]
        ]
    ]
];