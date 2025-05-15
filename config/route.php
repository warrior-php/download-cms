<?php
declare(strict_types=1);

use support\Request;
use Webman\Route;

// 禁用默认路由
Route::disableDefaultRoute();

Route::get('/', [App\Controllers\Index::class, 'index'])->name('index'); // 网站首页
Route::get('/install', [App\Controllers\Install\Index::class, 'index'])->name('install.index'); // 安装器

// 会员相关
Route::group('/user', function () {
    Route::add(['GET', 'POST'], '/register', [App\Controllers\User\Register::class, 'register'])->name('user.register'); // 注册
    Route::add(['GET', 'POST'], '/login', [App\Controllers\User\Authorize::class, 'login'])->name('user.login'); // 用户登录
    Route::add(['GET', 'POST'], '/forget', [App\Controllers\User\Authorize::class, 'forget'])->name('user.forget'); // 找回密码
    Route::add(['GET', 'POST'], '/logout', [App\Controllers\User\Authorize::class, 'logout'])->name('user.logout'); // 退出登录
    Route::add(['GET', 'POST'], '/emailVerify', [App\Controllers\User\Register::class, 'emailVerify'])->name('user.emailVerify'); // 验证邮箱
    Route::post('/hasEmail', [App\Controllers\User\Index::class, 'hasEmail'])->name('user.hasEmail'); // 检查email是否存在
    Route::post('/hasUsername', [App\Controllers\User\Index::class, 'hasUsername'])->name('user.hasUsername'); // 检查username是否存在
})->middleware([App\Middleware\Authorize::class]);

// 管理员相关
Route::group('/manages', function () {
    Route::get('/index', [App\Controllers\Manages\Index::class, 'index'])->name('manages.index');
});

// 404处理路由
Route::fallback(function (Request $request, $status) {
    $map = [
        404 => [
            'title'   => "Page Not Found",
            'content' => "It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. You might want to check your internet connection.",
        ],
        405 => [
            'title'   => "Method Not Allowed",
            'content' => "The requested HTTP method is not allowed for this endpoint.",
        ],
    ];

    $responseData = [
        'code'        => $status,
        'data'        => $map[$status],
        'request_url' => $request->uri(),
        'timestamp'   => time()
    ];

    return $request->expectsJson() ? json($responseData)->withStatus($status) : view('404', $responseData, 'public')->withStatus($status);
});