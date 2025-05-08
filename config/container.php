<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use support\Log;

try {
    $builder = new ContainerBuilder();

    // 加载自定义依赖定义（可选）
    $definitions = config('dependence', []);
    if (!empty($definitions)) {
        $builder->addDefinitions($definitions);
    }

    $builder->useAutowiring(true);  // 启用自动注入
    $builder->useAnnotations(true); // 启用注解支持

    return $builder->build();

} catch (Throwable $e) {
    // 写入日志
    Log::error('容器初始化失败: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

    // 可抛出异常给 Webman 的全局异常处理器处理
    throw new RuntimeException('容器初始化失败，请查看日志了解详情');
}