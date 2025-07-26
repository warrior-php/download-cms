<?php
declare(strict_types=1);

use Godruoyi\Snowflake\Snowflake;
use Ramsey\Uuid\Uuid;
use support\Response;

if (!function_exists('views_path')) {
    /**
     * views path
     *
     * @param string $path
     *
     * @return string
     */
    function views_path(string $path = 'view'): string
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

// 生成唯一标识符（UUID）
if (!function_exists('uuid')) {
    /**
     * 生成唯一标识符（UUID）。
     * 根据提供的算法类型生成不同格式的 UUID 或使用雪花算法生成唯一 ID。
     *
     * @param int        $type   算法类型
     *                           0：使用雪花算法生成唯一 ID（默认）。
     *                           1：基于时间的 UUID（UUID1）。
     *                           3：基于散列的 UUID（UUID3），使用 MD5 散列算法。
     *                           4：随机 UUID（UUID4）。
     *                           5：基于散列的 UUID（UUID5），使用 SHA1 散列算法。
     *                           6：基于主机 ID 和序列号的 UUID（UUID6）。
     *                           7：基于时间戳的 UUID（UUID7）。
     * @param bool       $number 是否返回数字形式的 UUID
     *                           true：返回整数形式的 UUID（适用于 UUID1、UUID3、UUID4、UUID5）。
     *                           false：返回字符串形式的 UUID（默认）。
     * @param int|string $data   用于生成 UUID 的附加数据（用于 UUID3、UUID5）
     *                           当 $type 为 3 或 5 时需要提供此数据。
     *
     * @return string 返回生成的 UUID 字符串或整数。
     *      - 如果 $number 为 true，返回整数形式的 UUID。
     *      - 如果 $number 为 false，返回标准字符串形式的 UUID。
     */
    function uuid(int $type = 0, bool $number = false, int|string $data = ''): string
    {
        // 雪花算法
        if ($type === 0) {
            $snowflake = new Snowflake();
            return $data . $snowflake->id();
        }

        // 生成不同类型的 UUID
        $uuid = match ($type) {
            1 => Uuid::uuid1(), // 基于时间生成 UUID
            3 => Uuid::uuid3(Uuid::NAMESPACE_DNS, $data), // 基于散列的 MD5 生成 UUID
            4 => Uuid::uuid4(), // 随机生成 UUID
            5 => Uuid::uuid5(Uuid::NAMESPACE_DNS, $data), // 基于 SHA1 生成 UUID
            6 => Uuid::uuid6(), // 基于主机 ID 和序列号生成 UUID
            default => Uuid::uuid7(), // 默认使用基于时间戳生成 UUID
        };

        // 根据是否要求数字形式返回 UUID
        return $number ? $uuid->getInteger()->toString() : $uuid->toString();
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
            $data['msg'] = $error;
        } else {
            $data['msg'] = $msg ?: $error;
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