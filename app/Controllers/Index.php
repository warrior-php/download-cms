<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\MailService;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Index
{
    /**
     * @Inject
     * @var MailService
     */
    protected MailService $mailService;

    /**
     * 网站首页
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): Response
    {
//        $result = $this->mailService->send(
//            ['weplus.cc@gmail.com' => '张三'],
//            '测试发送新邮件',
//            '<b>你好，这是正文内容</b>'
//        );
//        dump($result);
        return view('index');
    }
}