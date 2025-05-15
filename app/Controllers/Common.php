<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\UserModel;
use App\Rules\UserRule;
use App\Services\MailService;
use support\Request;
use support\Response;

class Common
{
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
    protected array $noNeedLogin = ['index', 'register', 'login', 'forget', 'emailVerify'];

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

    /**
     * 判断email是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    public function hasEmail(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasEmail($data['email'])) {
            return json(['error' => trans("This email address has been registered")]);
        }

        return json(['ok' => trans("Email can be registered")]);
    }

    /**
     * 判断username是否已注册
     *
     * @param Request $request
     *
     * @return Response
     */
    public function hasUsername(Request $request): Response
    {
        $data = $request->post();
        if (UserModel::hasUsername($data['username'])) {
            return json(['error' => trans("This username is already taken")]);
        }

        return json(['ok' => trans("Username can be registered")]);
    }
}