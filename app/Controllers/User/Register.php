<?php
declare(strict_types=1);

namespace App\Controllers\User;

use support\Redis;
use Exception;
use support\Request;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

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
    #[RateLimiter(limit: 1, ttl: 60, key: RateLimiter::SID, message: "The operation is too frequent, please scan and try again")]
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

            return result(302, ['url' => url('common.emailVerify')]);
        }

        return view('user/register');
    }
}