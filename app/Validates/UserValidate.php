<?php
declare(strict_types=1);

namespace App\Validates;

use Respect\Validation\Validator as v;

/**
 * 用户数据验证器
 *
 * 用于验证用户相关字段的数据格式是否符合要求，
 * 如用户名、邮箱、密码等。继承自通用验证基类 Validate。
 */
class UserValidate extends Validate
{
    /**
     * 定义各字段的验证规则
     *
     * @return array 字段名 => 对应的验证器规则
     */
    protected function rules(): array
    {
        return [
            // 用户名验证规则：
            // - 只能包含字母和数字
            // - 不允许有空格
            // - 长度必须在 4 到 18 个字符之间
            'username' => v::allOf(
                v::alnum()->setTemplate(trans("Usernames can only contain letters and numbers")),
                v::noWhitespace()->setTemplate(trans("Username cannot contain spaces")),
                v::length(4, 18)->setTemplate(trans("Username must be between 4 and 20 characters long")),
            ),

            // 邮箱验证规则：
            // - 必须为合法的邮箱格式
            'email'    => v::email()->setTemplate(trans("Please enter a valid email address")),

            // 密码验证规则：
            // - 必须是字符串类型
            // - 长度在 6 到 32 个字符之间
            'password' => v::allOf(
                v::stringType()->setTemplate(trans("Password must be a string")),
                v::length(6, 32)->setTemplate(trans("Password must be between 6 and 32 characters long"))
            ),
        ];
    }
}