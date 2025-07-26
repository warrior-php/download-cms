<?php
declare(strict_types=1);
// 英语
return [
    // 安装器相关
    'install' => [
        'index' => [
            'pageTitle'             => 'WarriorPHP Installation Wizard',
            'installedWarning'      => 'The system has been installed. To reinstall, delete the resources/install.lock file.',
            'installationWizard'    => 'Installation wizard',
            'databaseConfiguration' => 'Database configuration',
            'installationTips'      => 'Welcome to our Content Management System CMS Please make sure you have your database information ready including host username password and database name The installer will automatically check your environment and guide you through the database setup and admin account creation After installation please make sure to delete or rename the install directory to ensure your site security If you run into any issues during installation please refer to the documentation or contact our technical support team Thank you for choosing our CMS and enjoy building your site',
            'databaseAddress'       => 'Database address',
            'enterDatabaseHost'     => 'Please enter the database host address',
            'databasePort'          => 'Database port',
            'enterDatabasePort'     => 'Please enter the database port',
            'databaseUsername'      => 'Database username',
            'enterDatabaseUsername' => 'Please enter the database user name',
            'databasePassword'      => 'Database password',
            'enterDatabasePassword' => 'Please enter database password',
            'overwriteDatabase'     => 'Overwrite database',
            'cover'                 => 'Cover',
            'notCovered'            => 'Not covered',
            'nextStep'              => 'Next step',
        ],
    ],
    // 首页相关
    'home'    => [
        'index' => [
            'navigation' => '导航'
        ],
    ],
    // 管理相关
    'manage'  => [
        'index'   => [
            'login' => [
                'pageTitle'         => 'Admin Login',
                'emailAddress'      => 'Email Address',
                'enterEmail'        => 'Please enter your email address',
                'password'          => 'Password',
                'enterYourPassword' => 'Please enter your password',
                'rememberMe'        => 'Keep me logged in',
                'logIn'             => 'Log In',
                'captcha'           => 'Captcha',
                'enterCaptcha'      => 'Please enter the captcha code',
            ],
        ],
        // 验证相关
        'request' => [
            'email'          => 'Please enter a valid email address',
            'passwordString' => 'Password must be a string',
            'passwordLength' => 'Password must be between 6 and 32 characters long',
            'captchaLength'  => 'Captcha code length must be 5',
        ]
    ],
];