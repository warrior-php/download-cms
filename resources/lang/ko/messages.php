<?php
declare(strict_types=1);
// 韩语
return [
    // 安装器相关
    'install' => [
        'index' => [
            'pageTitle'             => 'WarriorPHP 설치 마법사',
            'installedWarning'      => '이미 설치가 완료되었습니다. 재설치를 원하시면 resources/install.lock 파일을 삭제해주세요.',
            'installationWizard'    => '설치 마법사',
            'databaseConfiguration' => '데이터베이스 설정',
            'installationTips'      => 'WarriorPHP 사용해주셔서 감사합니다! 먼저 데이터베이스 정보(주소, 사용자명, 비밀번호, DB명)를 준비해주세요. 시스템이 환경을 자동으로 점검하고 데이터베이스 초기화와 관리자 계정 생성을 도와드립니다. 설치가 끝나면 보안을 위해 반드시 install 폴더를 삭제하거나 이름을 변경해주세요. 설치 중 문제가 발생하면 설치 가이드를 참고하거나 고객센터로 문의해주세요. 즐거운 사용 되세요!',
            'databaseAddress'       => '데이터베이스 주소',
            'enterDatabaseHost'     => 'DB 호스트 주소를 입력해주세요',
            'databasePort'          => '데이터베이스 포트',
            'enterDatabasePort'     => 'DB 포트를 입력해주세요',
            'databaseUsername'      => 'DB 사용자명',
            'enterDatabaseUsername' => 'DB 사용자명을 입력해주세요',
            'databasePassword'      => 'DB 비밀번호',
            'enterDatabasePassword' => 'DB 비밀번호를 입력해주세요',
            'overwriteDatabase'     => '데이터베이스 덮어쓰기',
            'cover'                 => '덮어쓰기',
            'notCovered'            => '덮어쓰지 않음',
            'nextStep'              => '다음 단계',
        ],
    ],
    // 首页相关
    'home'    => [
        'index' => [
            'navigation' => '네비게이션'
        ],
    ],
    // 管理相关
    'manage'  => [
        'index'   => [
            'login' => [
                'pageTitle'         => '관리자 로그인',
                'emailAddress'      => '이메일 주소',
                'enterEmail'        => '이메일 주소를 입력해주세요',
                'password'          => '비밀번호',
                'enterYourPassword' => '비밀번호를 입력해주세요',
                'rememberMe'        => '로그인 상태 유지',
                'logIn'             => '로그인',
            ]
        ],
        // 验证相关
        'request' => [
            'email'          => '유효한 이메일 주소를 입력해주세요.',
            'passwordString' => '비밀번호는 문자로 입력해주세요.',
            'passwordLength' => '비밀번호는 6자 이상 32자 이하로 입력해주세요.',
        ]
    ],
];