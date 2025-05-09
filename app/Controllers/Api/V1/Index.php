<?php
declare(strict_types=1);

namespace App\Controllers\Api\V1;

class Index
{
    /**
     * @return string
     */
    public function index(): string
    {
        return 'Api 接口';
    }
}