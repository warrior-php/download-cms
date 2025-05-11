<?php
declare(strict_types=1);

namespace App\Rules;

use Respect\Validation\Validator as v;

/**
 * Class AuthorizeRule
 * 登录/授权相关的验证规则
 */
class AuthorizeRule extends Rule
{
    /**
     * 定义字段验证规则
     *
     * @return array 字段与其对应的验证器数组
     */
    protected function rules(): array
    {
        return [
            'username' => v::when(
                v::email(), // 如果是邮箱
                v::email()->setTemplate(trans("Please enter a valid email address")), // 则验证邮箱规则
                v::allOf( // 否则验证普通用户名规则
                    v::alnum()->setTemplate(trans("Usernames can only contain letters and numbers")),
                    v::noWhitespace()->setTemplate(trans("Username cannot contain spaces")),
                    v::length(4, 18)->setTemplate(trans("Username must be between 4 and 20 characters long")),
                ),
            ),

            'password' => v::allOf(
                v::stringType()->setTemplate(trans("Password must be a string")),
                v::length(6)->setTemplate(trans("Password must not be less than 6 characters"))
            ),
        ];
    }
}