<?php
declare(strict_types=1);

namespace App\Controllers\User;

use support\Redis;
use Exception;
use support\Request;
use support\Response;

class Register extends Common
{
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
            $this->userRule->validate($data);
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

            return result(200, '获取验证码成功');
        }

        return view('user/register');
    }
}