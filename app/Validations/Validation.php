<?php
declare(strict_types=1);

namespace App\Validations;

use Exception;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

abstract class Validation
{
    /**
     * 返回字段对应的验证规则
     *
     * @return array<string, Validator>
     */
    abstract protected function rules(): array;

    /**
     * 执行验证
     *
     * @param array|null $data 要验证的数据，默认取 request()->post()
     *
     * @throws Exception 如果验证失败
     */
    public function validate(?array $data = null): void
    {
        $data = $data ?? request()->post();

        $rules = $this->rules();

        foreach ($rules as $field => $validator) {
            // 跳过未传入的字段（比如部分更新、可选项）
            if (!array_key_exists($field, $data)) {
                continue;
            }

            try {
                $value = $data[$field];
                $validator->assert($value);
            } catch (NestedValidationException $e) {
                $messages = $e->getMessages();
                $error = array_values($messages)[0] ?? trans("Field validation failed");

                throw new Exception(message: $error, code: 422);
            }
        }
    }
}