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

// 解析 Accept-Language HTTP 请求头来获取用户浏览器或设备的首选语言
if (!function_exists('getPreferredLanguage')) {
    /**
     * 解析 Accept-Language HTTP 请求头来获取用户浏览器或设备的首选语言
     *
     * @return int|null|string
     * @throws Exception
     */
    function getPreferredLanguage(): int|string|null
    {
        // 从HTTP请求头获取Accept-Language信息
        $acceptLanguage = request()->header('accept-language');
        $languages = [];

        // 解析所有语言及其权重
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';', $lang);
            $code = trim($parts[0]);
            $weight = 1.0; // 默认权重

            if (isset($parts[1]) && str_starts_with($parts[1], 'q=')) {
                $weight = (float)substr($parts[1], 2);
            }

            $languages[$code] = $weight;
        }

        // 按权重降序排序
        arsort($languages);

        // 权重最高的语言代码
        $language = key($languages) ?: 'en'; // 默认英语

        // 将最终确定的语言设置存入session，避免重复检测
        session()->set('lang', $language);

        // 获取系统配置的备用语言列表
        $fallback_locale = config('translation.fallback_locale');
        $normalizedCode = str_replace('-', '_', $language);

        // 验证检测到的语言是否在系统支持的语言列表中
        if (!in_array($normalizedCode, $fallback_locale)) {
            // 如果不支持，则使用系统默认的第一个备用语言
            $normalizedCode = $fallback_locale[0];
        }

        return $normalizedCode;
    }
}

// Result
if (!function_exists('result')) {
    /**
     * @param int    $code
     * @param string $msg
     * @param array  $data
     *
     * @return Response
     */
    function result(int $code, string $msg = 'ok', array $data = []): Response
    {
        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
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
