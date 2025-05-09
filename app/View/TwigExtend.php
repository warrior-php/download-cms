<?php

namespace App\View;

use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtend extends AbstractExtension
{
    /**
     * 注册自定义 TwigExtend 函数
     *
     * @return array 返回包含 TwigFunction 实例的数组
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('__PUBLIC__', [$this, 'getPublicPath']), // 注册一个名为 "__PUBLIC__" 的 Twig 模板函数，
            new TwigFunction('trans', [$this, 'generateTrans']), // 多语言
            new TwigFunction('url', [$this, 'generateUrl']),
            new TwigFunction('csrf_token', [$this, 'generateCsrfToken'])
        ];
    }

    /**
     * Trans
     *
     * @param string $key
     *
     * @return string
     */
    public function generateTrans(string $key): string
    {
        return trans($key);
    }

    /**
     * 获取静态资源路径
     *
     * @param string $path 可选的相对资源路径，默认值为空字符串
     *
     * @return string 返回格式化后的路径字符串，确保前缀为单个 `/`
     */
    public function getPublicPath(string $path = ''): string
    {
        return '/' . ltrim($path, '/');
    }

    /**
     * URL
     *
     * @param string $path   路径，例如 'admin/index/index'
     * @param array  $params 参数，例如 ['id' => 1]
     *
     * @return string
     */
    public function generateUrl(string $path, array $params = []): string
    {
        return url($path, $params);
    }

    /**
     * 生成csrf_token
     *
     * @return string
     * @throws Exception
     */
    public function generateCsrfToken(): string
    {
        return buildToken();
    }
}