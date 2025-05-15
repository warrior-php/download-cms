<?php
declare(strict_types=1);

namespace App\Controllers\User;

use App\Controllers\Common;

class Index extends Common
{
    /**
     * @return string
     */
    public function index(): string
    {
        return 'user index';
    }
}