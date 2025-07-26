<?php
declare(strict_types=1);

namespace App\Request;

use Respect\Validation\Validator as v;

/**
 * 用户相关字段验证规则类 Request
 *
 * 用于校验用户注册、登录等场景中的字段，如用户名、邮箱、密码等。
 * 继承自抽象类 App\Request\Request，支持场景切换及字段级别验证。
 */
class User extends Validator
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
            'username' => v::when(
                v::email(), // 条件：是邮箱格式
                v::alwaysValid(), // 如果是邮箱，跳过后续验证
                v::allOf(
                    v::alnum()->setTemplate(trans("Usernames can only contain letters and numbers")),
                    v::noWhitespace()->setTemplate(trans("Username cannot contain spaces")),
                    v::length(4, 18)->setTemplate(trans("Username must be between 4 and 18 characters long")),
                ),
            ),
            'email'    => v::email()->setTemplate(trans("Please enter a valid email address")),
            'password' => v::allOf(
                v::stringType()->setTemplate(trans("Password must be a string")),
                v::length(6, 32)->setTemplate(trans("Password must be between 6 and 32 characters long"))
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
            'register' => ['username', 'email', 'password'],
            'login'    => ['username', 'password'],
        ];
    }
}