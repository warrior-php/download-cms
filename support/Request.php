<?php

namespace support;

use Exception;

/**
 * Class Request
 *
 * @package support
 */
class Request extends \Webman\Http\Request
{
    /**
     * 生成请求令牌
     *
     * @param string $name 令牌名称
     * @param string $type 令牌生成方法
     *
     * @return string
     * @throws Exception
     */
    public function buildToken(string $name = '__token__', string $type = 'md5'): string
    {
        $type = is_callable($type) ? $type : 'md5';
        // 获取当前时间戳，精确到微秒
        $token = call_user_func($type, microtime(true));
        session()->set($name, $token);
        return $token;
    }

    /**
     * 检查请求令牌
     *
     * @access public
     *
     * @param string $token 令牌名称
     * @param array  $data  表单数据
     *
     * @return bool
     * @throws Exception
     */
    public function checkToken(string $token = '__token__', array $data = []): bool
    {
        // 如果请求方法是 GET、HEAD 或 OPTIONS，直接返回 true
        if (in_array($this->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return true;
        }
        // 检查 session 中是否存在指定的令牌
        if (!session()->has($token)) {
            // 令牌数据无效
            return false;
        }
        // 验证 Header 中的 CSRF 令牌
        if ($this->header('X-CSRF-TOKEN') && session()->get($token) === $this->header('X-CSRF-TOKEN')) {
            // 防止重复提交
            session()->delete($token); // 验证完成销毁 session 中的令牌
            return true;
        }
        // 如果 data 为空，则使用 POST 数据进行验证
        if (empty($data)) {
            $data = $this->post();
        }
        // 验证表单数据中的令牌
        if (isset($data[$token]) && session()->get($token) === $data[$token]) {
            // 防止重复提交
            session()->delete($token); // 验证完成销毁 session 中的令牌
            return true;
        }
        // 令牌验证失败，清除 session 中的令牌
        session()->delete($token);
        return false;
    }
}