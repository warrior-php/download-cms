<?php
declare(strict_types=1);

use App\View\TwigExtend;
use App\View\TwigView;

return [
    'handler' => TwigView::class,
    'options' => [
        'debug'       => true, // ✅ 开发阶段建议开启 debug
        'cache'       => runtime_path() . '/views', // 缓存目录，正常设置即可
        'auto_reload' => true, // ✅ 启用自动重新编译模板
        'view_suffix' => 'Twig',
        'charset'     => 'UTF-8',
    ],

    'extension' => function ($twig) {
        $twig->addExtension(new TwigExtend());
    }
];
