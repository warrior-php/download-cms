<?php
declare(strict_types=1);

namespace App\Controller;

class Home extends Common
{
    /**
     * 网站首页
     *
     * @return string
     */
    public function index(): string
    {
        return 'Welcome to Warrior PHP';
    }
}