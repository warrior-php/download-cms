<?php
declare(strict_types=1);

namespace App\Request;

use Respect\Validation\Validator as v;

class Install extends Validator
{
    /**
     * 定义所有字段的通用验证规则
     * 通过 scene() 方法可筛选特定场景下的字段。
     *
     * @return array<string, v>
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * 定义不同业务场景下使用的字段
     * 每个场景对应字段名数组，会在调用 scene() 方法后按需取用。
     *
     * @return array<string, string[]>
     */
    protected function scenes(): array
    {
        return [];
    }
}