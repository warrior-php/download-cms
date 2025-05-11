<?php
declare(strict_types=1);

namespace App\Controllers\User;

use App\Rules\UserRule;
use App\Services\MailService;
use App\Services\UserService;

class Common
{
    /**
     * 注入验证依赖
     *
     * @Inject
     * @var UserService
     */
    protected UserService $userService;

    /**
     * 注入验证依赖
     *
     * @Inject
     * @var MailService
     */
    protected MailService $mailService;

    /**
     * 注入验证依赖
     *
     * @Inject
     * @var UserRule
     */
    protected UserRule $userRule;

    /**
     * 无需登录的操作列表
     *
     * 控制器中定义的动作名称（如方法名），列在此数组中时，
     * 用户即使未登录也可以访问这些动作。
     *
     * 示例：
     * - login：登录页面或操作
     * - logout：退出操作
     *
     * @var string[]
     */
    protected array $noNeedLogin = ['register', 'login', 'forget', 'checkUniqueness'];

    /**
     * 无需鉴权的操作列表
     *
     * 用户虽然需要登录，但不必验证具体权限的操作列表。
     * 通常用于首页仪表盘、公告页等不敏感模块。
     *
     * 示例：
     * - dashboard：系统仪表盘页面
     *
     * @var string[]
     */
    protected array $noNeedAuth = ['logout'];
}