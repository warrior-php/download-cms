<?php
declare(strict_types=1);

namespace App\Middleware;

use Exception;
use ReflectionClass;
use ReflectionException;
use Webman\MiddlewareInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class Authorize implements MiddlewareInterface
{
    /**
     * 请求处理主方法，Webman 会自动调用此方法处理请求链。
     *
     * @param Request  $request 当前 HTTP 请求实例
     * @param callable $handler 请求处理闭包，执行后续中间件或控制器
     *
     * @return Response 响应对象
     * @throws Exception 若控制器类不存在或无权限访问
     * @throws ReflectionException 若反射控制器失败
     */
    public function process(Request $request, callable $handler): Response
    {
        $controllerClass = $request->controller;
        $action = $request->action;
        if (!isset($controllerCache[$controllerClass])) {
            $reflection = new ReflectionClass($controllerClass);
            $controllerCache[$controllerClass] = $reflection->getDefaultProperties()['noNeedLogin'] ?? [];
        }
        $noNeedLogin = $controllerCache[$controllerClass];
        $isAdmin = str_contains($controllerClass, 'Admin');
        $sessionKey = $isAdmin ? 'admin' : 'user';
        $loginUrl = $isAdmin ? url('admin.account.login') : url('user.login');
        $loggedInRedirectUrl = $isAdmin ? url('admin.index') : $loginUrl;
        $isLoggedIn = session($sessionKey);
        // 情况 1：当前 action 不在免登录列表，已登录则放行，未登录则跳转到对应的登录页
        if (!in_array($action, $noNeedLogin)) {
            return $isLoggedIn ? $handler($request) : redirect($loginUrl);
        }
        // 情况 2：当前 action 是 login，但用户已登录，直接重定向至首页，避免重复登录
        if ($action === 'login' && $isLoggedIn) {
            return redirect($loggedInRedirectUrl);
        }
        // 情况 3：当前 action 在免登录列表中，直接放行
        return $handler($request);
    }
}