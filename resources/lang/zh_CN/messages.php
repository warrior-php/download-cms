<?php
declare(strict_types=1);
// 中文简体
return [
    // 安装器相关
    'install' => [
        'index' => [
            'pageTitle'             => 'WarriorPHP 安装向导',
            'installedWarning'      => '系统已安装完毕，如需重新安装，请删除 resources/install.lock 文件。',
            'installationWizard'    => '安装向导',
            'databaseConfiguration' => '数据库配置',
            'installationTips'      => '欢迎使用本内容管理系统！请提前准备好数据库信息（数据库地址、用户名、密码、数据库名），系统将自动检测您的环境配置，并引导您完成数据库初始化和管理员账户创建。安装后塍后，请务必删除或重命名安装目录（install），以确保系统安全。安装过程中遇到任何问题，请查看安装文档或联系我们的技术支持。祝您使用愉快！',
            'databaseAddress'       => '数据库地址',
            'enterDatabaseHost'     => '请输数据库host地址',
            'databasePort'          => '数据库端口',
            'enterDatabasePort'     => '请输数据库端口',
            'databaseUsername'      => '数据库用户名',
            'enterDatabaseUsername' => '请输数据库用户名',
            'databasePassword'      => '数据库密码',
            'enterDatabasePassword' => '请输入数据库密码',
            'overwriteDatabase'     => '覆盖数据库',
            'cover'                 => '覆盖',
            'notCovered'            => '不覆盖',
            'nextStep'              => '下一步',
        ],
    ],
    // 首页相关
    'home'    => [
        'index' => [
            'navigation' => '导航'
        ]
    ],
    // 管理相关
    'manage'  => [
        'index'   => [
            'login' => [
                'pageTitle'         => '管理员登录',
                'emailAddress'      => 'Email 地址',
                'enterEmail'        => '请输入 Email 地址',
                'password'          => '密码',
                'enterYourPassword' => '输入你的密码',
                'rememberMe'        => '记住账号',
                'logIn'             => '登录',
                'captcha'           => '验证码',
            ],
        ],
        // 验证相关
        'request' => [
            'email'          => '请输入有效的邮箱地址',
            'passwordString' => '密码必须是字符串',
            'passwordLength' => '密码长度需在6到32个字符之间',
        ]
    ],
];