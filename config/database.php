<?php
declare(strict_types=1);

return [
    // 默认数据库配置
    'default'     => 'mysql',  // 设置默认数据库连接为mysql

    // 各种数据库配置
    'connections' => [
        'mysql' => [
            'driver'      => getenv('DATABASE_TYPE'), // 数据库驱动类型，这里使用MySQL
            'host'        => getenv('DATABASE_HOST'), // 数据库主机地址，默认使用本地
            'port'        => getenv('DATABASE_PORT'), // MySQL服务端口，默认是3306
            'database'    => getenv('DATABASE_NAME'), // 数据库名称，设置为test
            'username'    => getenv('DATABASE_USERNAME'), // 数据库用户名，设置为root
            'password'    => getenv('DATABASE_PASSWORD'), // 数据库密码，空密码
            'unix_socket' => '', // Unix Socket路径，一般用于Linux系统连接
            'charset'     => 'utf8mb4', // 设置数据库字符集为utf8
            'collation'   => 'utf8mb4_0900_ai_ci', // 设置字符集排序规则为utf8_unicode_ci
            'prefix'      => getenv('DATABASE_PREFIX'), // 数据表前缀，空表示没有前缀
            'strict'      => true, // 是否开启严格模式，启用后会严格检查SQL语法
            'engine'      => null, // MySQL存储引擎，默认使用InnoDB，如果不需要可设置为null
            'options'     => [
                PDO::ATTR_EMULATE_PREPARES => false, // 禁用PDO的模拟预处理语句，建议使用原生SQL
            ],

            'pool' => [
                'max_connections'    => 5,
                'min_connections'    => 1,
                'wait_timeout'       => 3,
                'idle_timeout'       => 60,
                'heartbeat_interval' => 50,
            ],
        ]
    ]
];