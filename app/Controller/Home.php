<?php
declare(strict_types=1);

namespace App\Controller;

use support\Response;

class Home extends Common
{
    /**
     * 网站首页
     *
     * @return Response
     */
    public function index(): Response
    {
        return view('index');
    }
}