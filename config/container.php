<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use support\Log;

try {
    $builder = new ContainerBuilder();
    // 添加依赖到容器中
    $builder->addDefinitions(config('dependence', []));
    // 自动注入允许容器根据类型提示自动解析和注入依赖项
    $builder->useAutowiring(true);
    // 启用 PHP 8 中的属性（Attributes）功能
    $builder->useAttributes(true);
    return $builder->build();
} catch (Throwable $e) {
    Log::error('容器初始化失败：', ['error' => $e->getMessage()]);
    exit(1);
}