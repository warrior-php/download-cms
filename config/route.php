<?php
declare(strict_types=1);

use support\Request;
use Webman\Route;

// 禁用默认路由
Route::disableDefaultRoute();

Route::get('/', [App\Controller\Home::class, 'index'])->name('index'); // 网站首页
Route::get('/install', [App\Controller\Install\Index::class, 'index'])->name('install.index'); // 安装器

//// 会员相关
//Route::group('/user', function () {
//    Route::add(['GET', 'POST'], '/register', [App\Controller\User\Home::class, 'register'])->name('user.register'); // 注册
//    Route::add(['GET', 'POST'], '/login', [App\Controller\User\Home::class, 'login'])->name('user.login'); // 用户登录
//    Route::add(['GET', 'POST'], '/forget', [App\Controller\User\Home::class, 'forget'])->name('user.forget'); // 找回密码
//    Route::add(['GET', 'POST'], '/logout', [App\Controller\User\Home::class, 'logout'])->name('user.logout'); // 退出登录
//    Route::add(['GET', 'POST'], '/emailVerify', [App\Controller\User\Home::class, 'emailVerify'])->name('user.emailVerify'); // 验证邮箱
//    Route::post('/hasEmail', [App\Controller\User\Home::class, 'hasEmail'])->name('user.hasEmail'); // 检查email是否存在
//    Route::post('/hasUsername', [App\Controller\User\Home::class, 'hasUsername'])->name('user.hasUsername'); // 检查username是否存在
//    Route::add(['GET', 'POST'], '/index', [App\Controller\User\Home::class, 'index'])->name('user.index'); // 用户首页
//})->middleware([App\Middleware\Authorize::class]);

// 公共相关
Route::group('/common', function () {
    Route::get('/captcha', [App\Controller\Common::class, 'captcha'])->name('common.captcha'); // 验证码
})->middleware([App\Middleware\Authorize::class]);

// 管理员相关
Route::group('/manage', function () {
    Route::get('/index', [App\Controller\Manage\Index::class, 'index'])->name('manage.index');
    Route::add(['GET', 'POST'], '/login', [App\Controller\Manage\Index::class, 'login'])->name('manage.login'); // 管理员登录
})->middleware([App\Middleware\Authorize::class]);

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