<?php
declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;

class Handler extends \support\exception\Handler
{
    /**
     * 渲染返回
     *
     * @param Request   $request
     * @param Throwable $exception
     *
     * @return Response
     */
    public function render(Request $request, Throwable $exception): Response
    {
        $code = $exception->getCode() ?: 500;

        // AJAX 请求返回 JSON
        if ($request->expectsJson()) {
            return json(['code' => $code, 'msg' => $exception->getMessage()]);
        }

        // 如果开启了 debug 模式，使用默认的错误处理方式
        if (config('app.debug', false)) {
            return parent::render($request, $exception);
        }

        // 未开启 debug 时，渲染自定义错误页面
        return view('500', [
            'exception' => $exception,
            'debug'     => [
                'message'       => $exception->getMessage(),
                'code'          => $code,
                'file'          => $exception->getFile(),
                'line'          => $exception->getLine(),
                'trace'         => $exception->getTrace(),
                'traceAsString' => $exception->getTraceAsString(),
            ]
        ])->withStatus($code);
    }
}