<?php
declare(strict_types=1);

namespace App\Controllers\User;

use Exception;
use support\Redis;
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
            $body = "注册验证码：{$code}，请在 $expire 分钟内完成注册。如果您没有发起请求，请忽略此邮件。";
            // 发送邮件
            if ($this->mailService->send($data['email'], trans("Welcome! Please verify your email"), $body)) {
                Redis::set('sms:' . $data['email'], $code, $expire);
            }

            return result(200, '获取验证码成功');
        }

        return view('user/register');
    }
}