<?php
declare(strict_types=1);

namespace App\Controllers;

use support\Response;

class Index extends Common
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