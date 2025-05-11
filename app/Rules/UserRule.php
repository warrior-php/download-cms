<?php
declare(strict_types=1);

namespace App\Rules;

use Respect\Validation\Validator as v;

/**
 * 用户数据验证器
 *
 * 用于验证用户相关字段的数据格式是否符合要求，
 * 如用户名、邮箱、密码等。继承自通用验证基类 Rule。
 */
class UserRule extends Rule
{
    /**
     * 定义各字段的验证规则
     *
     * @return array 字段名 => 对应的验证器规则
     */
    protected function rules(): array
    {
        return [
            'username' => v::when(
                v::email(), // 如果是邮箱
                v::alwaysValid(), // 如果是邮箱就跳过，代表它已经满足邮箱格式了
                v::allOf( // 否则验证普通用户名规则
                    v::alnum()->setTemplate(trans("Usernames can only contain letters and numbers")),
                    v::noWhitespace()->setTemplate(trans("Username cannot contain spaces")),
                    v::length(4, 18)->setTemplate(trans("Username must be between 4 and 18 characters long")),
                ),
            ),

            'email' => v::email()->setTemplate(trans("Please enter a valid email address")),


            'password' => v::allOf(
                v::stringType()->setTemplate(trans("Password must be a string")),
                v::length(6, 32)->setTemplate(trans("Password must be between 6 and 32 characters long"))
            ),
        ];
    }
}