<?php
declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\Common;
use App\Model\UserModel;
use App\Service\MailService;
use Exception;
use support\Redis;
use support\Request;
use support\Response;

class Index extends Common
{
    /**
     * @return string
     */
    public function index(): string
    {
        return 'user index';
    }

    /**
     * 注入验证依赖
     *
     * @Inject
     * @var MailService
     */
    protected MailService $mailService;

    /**
     * 用户注册
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function register(Request $request): Response
    {
        if ($request->isAjax()) {
            $data = request()->post();
            $this->validate('User', $data, 'register');
            session()->set('register', $data);

            // 发送邮件验证码
            $expire = 600; // 验证码有效期，单位：秒
            $code = generateCode(); // 生成验证码
            $body = trans("Email verification code template", [
                '%code%'   => $code,
                '%expire%' => $expire / 60
            ]);

            if ($this->mailService->send($data['email'], trans("Welcome! Please verify your email"), $body)) {
                Redis::set('sms:' . $data['email'], $code, $expire);
            }

            return result(302, ['url' => url('user.emailVerify')]);
        }

        return view('user/register');
    }

    /**
     * 注册邮箱验证
     *
     * @return Response
     */
    public function emailVerify(): Response
    {
        return view('user/email_verify');
    }

    /**
     * 登录
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function login(Request $request): Response
    {
        if ($request->isAjax()) {
            $data = request()->post();
            $this->validate('User', $data, 'login');
        }
        return view('user/login');
    }

    /**
     * 登出
     *
     * @return string
     */
    public function logout(): string
    {
        return '退出';
    }

    /**
     * 找回密码
     *
     * @return string
     */
    public function forget(): string
    {
        return '退出';
    }

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