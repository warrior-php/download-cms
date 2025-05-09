<?php
declare(strict_types=1);

use support\Request;
use Webman\Route;

// 禁用默认路由
Route::disableDefaultRoute();

Route::get('/', [App\Controllers\Index::class, 'index'])->name('index'); // 网站首页
Route::get('/install', [App\Controllers\Install\Index::class, 'index'])->name('install.index'); // 安装器

// 授权认证相关
Route::group('/authorize', function () {
    Route::add(['GET', 'POST'], '/login', [App\Controllers\Authorize\Index::class, 'login'])->name('authorize.login'); // 登入
    Route::add(['GET', 'POST'], '/forget', [App\Controllers\Authorize\Index::class, 'forget'])->name('authorize.forget'); // 找回密码
    Route::get('/logout', [App\Controllers\Authorize\Index::class, 'logout'])->name('authorize.logout'); // 登出
})->middleware([App\Middleware\Authorize::class]);

// 会员相关
Route::group('/user', function () {
    Route::add(['GET', 'POST'], '/register', [App\Controllers\User\Register::class, 'register'])->name('user.register'); // 注册
})->middleware([App\Middleware\Authorize::class]);

// 管理员相关
Route::group('/manages', function () {
    Route::get('/index', [App\Controllers\Manages\Index::class, 'index'])->name('manages.index');
});

// Api相关
Route::group('/api', function () {
    Route::get('/index', [\App\Controllers\Api\V1\Index::class, 'index'])->name('api.index');
});

// 404处理路由
Route::fallback(function (Request $request, $status) {
    $map = [
        404 => [
            'title'   => "Page Not Found",
            'content' => "It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. You might want to check your internet connection.",
        ]
    ];

    $responseData = [
        'code'        => $status,
        'data'        => $map[$status],
        'request_url' => $request->uri(),
        'timestamp'   => time()
    ];

    return $request->expectsJson() ? json($responseData)->withStatus($status) : view('404', $responseData, 'Http')->withStatus($status);
});