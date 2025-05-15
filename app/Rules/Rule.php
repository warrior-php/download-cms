<?php
declare(strict_types=1);

namespace App\Rules;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use support\exception\BusinessException;

/**
 * 验证规则基类（支持场景选择与字段规则验证）
 *
 * 所有自定义的规则类（如 UserRule）需继承该抽象类，并实现 rules() 方法定义字段验证规则。
 * 同时可选实现 scenes() 方法用于支持按场景选择字段。
 *
 * 使用方式：
 *   $rule = new XxxRule();
 *   $rule->scene('register')->validate($data);
 */
abstract class Rule
{
    /**
     * 当前选择的场景字段名列表
     *
     * 如果调用了 scene() 方法，则仅验证该场景中定义的字段。
     * 否则默认验证所有字段。
     *
     * @var string[]
     */
    protected array $scene = [];

    /**
     * 设置当前验证场景
     *
     * 场景通过 scenes() 方法返回的字段列表进行匹配。
     *
     * @param string $name 场景名称
     *
     * @return static 返回当前实例，便于链式调用
     */
    public function scene(string $name): static
    {
        $this->scene = $this->scenes()[$name] ?? [];
        return $this;
    }

    /**
     * 执行数据验证
     *
     * 会根据 rules() 中定义的规则对传入数据进行字段验证。
     * 如果调用了 scene()，则只验证该场景下的字段。
     * 验证失败会抛出 BusinessException 异常。
     *
     * @param array $data 待验证的数据数组
     *
     * @throws BusinessException 验证失败时抛出，消息为首条错误信息
     */
    public function validate(array $data): void
    {
        $rules = $this->rules();

        // 如果设置了场景，则只验证该场景下的字段
        if (!empty($this->scene)) {
            $rules = array_intersect_key($rules, array_flip($this->scene));
        }

        foreach ($rules as $field => $validator) {
            try {
                $validator->assert($data[$field] ?? null);
            } catch (NestedValidationException $e) {
                $arr = $e->getMessages();
                throw new BusinessException(reset($arr));
            }
        }
    }

    /**
     * 定义所有字段的验证规则
     *
     * 子类必须实现，返回字段 => 验证器 的键值对数组。
     *
     * @return array<string, Validator>
     */
    abstract protected function rules(): array;

    /**
     * 定义不同业务场景下需要验证的字段（可选）
     *
     * 子类可覆盖该方法，返回场景 => 字段数组 的映射。
     *
     * @return array<string, string[]>
     */
    protected function scenes(): array
    {
        return [];
    }
}