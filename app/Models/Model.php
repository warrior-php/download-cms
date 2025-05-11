<?php
declare(strict_types=1);

namespace App\Models;

use support\exception\BusinessException;

class Model extends \support\Model
{
    /**
     * 校验必填字段
     *
     * @param array $data
     * @param array $requiredFields
     *
     * @throws BusinessException
     */
    public static function validateRequiredFields(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                throw new BusinessException("字段 $field 不能为空");
            }
        }
    }
}