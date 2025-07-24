<?php
declare(strict_types=1);

namespace App\Controller\Install;

use support\Response;

class Index
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return view('install/index');
    }
}