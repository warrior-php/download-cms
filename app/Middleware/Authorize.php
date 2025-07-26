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
     * @param Request  $request
     * @param callable $handler
     *
     * @return Response
     * @throws Exception
     * @throws ReflectionException
     */
    public function process(Request $request, callable $handler): Response
    {
        $controllerClass = $request->controller;
        $action = $request->action;
        $isAdmin = str_contains($controllerClass, 'Admin');
        $sessionKey = $isAdmin ? 'admin' : 'user';
        $redirectUrl = $isAdmin ? url('admin.account.login') : url('user.login');
        // 已登录直接放行
        if (session($sessionKey)) {
            return $handler($request);
        }
        // 通过反射获取控制器的 noNeedLogin 属性
        $reflection = new ReflectionClass($controllerClass);
        $noNeedLogin = $reflection->getDefaultProperties()['noNeedLogin'] ?? [];
        // 如果当前 action 不在免登录列表，拦截重定向
        if (!in_array($action, $noNeedLogin)) {
            return redirect($redirectUrl);
        }
        // 不需要登录，放行
        return $handler($request);
    }
}