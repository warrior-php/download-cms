<?php
declare(strict_types=1);

use support\Response;

if (!function_exists('views_path')) {
    /**
     * views path
     *
     * @param string $path
     *
     * @return string
     */
    function views_path(string $path = 'views'): string
    {
        return path_combine(BASE_PATH . DIRECTORY_SEPARATOR . 'resources', $path);
    }
}

// 生成URL
if (!function_exists('url')) {
    /**
     * 生成URL
     *
     * @param string $name  路由名称
     * @param array  $param 路由参数
     *
     * @return string 生成的URL
     */
    function url(string $name = '', array $param = []): string
    {
        $route = route($name, $param);

        // Check if the route is '/'
        if ($route === '/') {
            return $route;
        }

        // Apply rtrim to remove trailing slashes
        return rtrim($route, '/');
    }
}

// 设置语言环境
if (!function_exists('setupLocale')) {
    /**
     * 初始化语言环境，并设置到 session 中
     *
     * @param $acceptLang
     *
     * @return string
     * @throws Exception
     */
    function setupLocale($acceptLang): string
    {
        $defaultLang = config('translation.locale');
        $browserLang = str_replace('-', '_', parseAcceptLanguage($acceptLang));
        $supported = config('translation.fallback_locale');
        if (in_array($browserLang, $supported, true)) {
            $language = $browserLang;
        } else {
            $language = $defaultLang;
        }
        session()->set('lang', $language);
        return $language;
    }
}

// 解析 Accept-Language HTTP 请求头来获取用户浏览器或设备的首选语言
if (!function_exists('parseAcceptLanguage')) {
    /**
     * @param string $acceptLanguage
     *
     * @return string
     */
    function parseAcceptLanguage(string $acceptLanguage): string
    {
        if (empty($acceptLanguage)) {
            return '';
        }
        $langs = explode(',', $acceptLanguage);
        if (empty($langs)) {
            return '';
        }
        $primary = explode(';', trim($langs[0]))[0];
        if ($primary === 'zh') {
            return 'zh-CN';
        }
        return $primary;
    }
}

// Result
if (!function_exists('result')) {
    /**
     * @param int          $code
     * @param string|array $msg
     * @param array        $var
     *
     * @return Response
     */
    function result(int $code, string|array $msg = '', array $var = []): Response
    {
        $message = config('code', false);
        $error = $message[$code] ?? $message[10001];
        if (is_array($msg) || is_object($msg)) {
            $var = $msg;
            $data['message'] = $error;
        } else {
            $data['message'] = $msg ?: $error;
        }

        if (isset($var['url'])) {
            $data['url'] = $var['url'];
        }

        //控制返回的参数后台是否执行iframe父层
        if (isset($var['is_parent'])) {
            $data['is_parent'] = $var['is_parent'];
        }
        $data['code'] = $code;
        $data['data'] = $var;

        return new Response(200, ['Content-Type' => 'application/json'], json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}

// 生成请求令牌
if (!function_exists('')) {
    /**
     * 生成请求令牌
     *
     * @param string $name 令牌名称
     * @param string $type 令牌生成方法
     *
     * @return string
     * @throws Exception
     */
    function buildToken(string $name = '__token__', string $type = 'md5'): string
    {
        // 确保 $type 是一个有效的回调函数或字符串类型（默认使用 'md5'）
        $type = is_callable($type) ? $type : 'md5';

        // 将 microtime (true) 转换为字符串，避免传递 float 给 md5
        $token = call_user_func($type, (string)microtime(true));  // 强制转换为字符串

        // 保存生成的令牌到会话中
        session()->set($name, $token);

        return $token;
    }
}

// 生成指定长度的数字验证码（使用字符串拼接）
if (!function_exists('generateCode')) {
    /**
     * 生成指定长度的数字验证码（使用字符串拼接）
     *
     * @param int $length 验证码长度，默认6位
     *
     * @return string 生成的验证码（如：074319）
     */
    function generateCode(int $length = 6): string
    {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }
}