<?php
declare(strict_types=1);

namespace App\Controllers\User;

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
     */
    public function register(Request $request): Response
    {
        if ($request->isAjax()) {
            $data = request()->post();
            $this->userRule->validate();
            $this->userService->register($data);
        }

        return view('user/register');
    }
}