<?php
declare(strict_types=1);

namespace App\Request;

use Respect\Validation\Validator as v;

class Admin extends Validator
{
    /**
     * 定义所有字段的通用验证规则
     * 通过 scene() 方法可筛选特定场景下的字段。
     *
     * @return array<string, v>
     */
    protected function rules(): array
    {
        return [
            'email'    => v::email()->setTemplate(trans('admin.request.key001')),
            'password' => v::allOf(
                v::stringType()->setTemplate(trans('admin.request.key002')),
                v::length(6, 32)->setTemplate(trans('admin.request.key003'))
            ),
            'captcha'  => v::allOf(
                v::stringType()->setTemplate(trans('admin.request.key004')),
                v::length(5, 5)->setTemplate(trans('admin.request.key005'))
            ),
        ];
    }

    /**
     * 定义不同业务场景下使用的字段
     *
     * 每个场景对应字段名数组，会在调用 scene() 方法后按需取用。
     *
     * @return array<string, string[]>
     */
    protected function scenes(): array
    {
        return [
            'login' => ['email', 'password', 'captcha'],
        ];
    }
}