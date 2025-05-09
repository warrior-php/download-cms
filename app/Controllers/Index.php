<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\MailgunService;
use support\Response;
use Warrior\RateLimiter\Annotation\RateLimiter;

class Index
{
    /**
     * 注入验证依赖
     *
     * @Inject
     * @var MailgunService
     */
    protected MailgunService $mailgun;

    /**
     * 网站首页
     *
     * @return Response
     */
    #[RateLimiter(limit: 3, ttl: 1)]
    public function index(): Response
    {
        return view('index');
    }
}