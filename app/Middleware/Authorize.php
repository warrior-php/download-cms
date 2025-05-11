<?php
declare(strict_types=1);

namespace App\Middleware;

use Exception;
use ReflectionClass;
use ReflectionException;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

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
        $controller = $request->controller;
        $action = $request->action;
        $code = 0;
        $msg = trans("Unknown Error");

        if (session('user')) {
            // 已经登录，请求继续向洋葱芯穿越
            return $handler($request);
        }

        // 通过反射获取控制器哪些方法不需要登录
        $controller = new ReflectionClass($request->controller);
        $noNeedLogin = $controller->getDefaultProperties()['noNeedLogin'] ?? [];

        // 访问的方法需要登录
        if (!in_array($request->action, $noNeedLogin)) {
            // 拦截请求，返回一个重定向响应，请求停止向洋葱芯穿越
            return redirect(url('user.login'));
        }

        // 不需要登录，请求继续向洋葱芯穿越
        return $handler($request);
    }
}