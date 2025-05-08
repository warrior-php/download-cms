<?php
declare(strict_types=1);

namespace App\Middleware;

use Exception;
use support\exception\BusinessException;
use support\View;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class InitApp implements MiddlewareInterface
{
    /**
     * 系统版本
     */
    protected const string VERSION = 'v0.1.0';

    /**
     * 应用名称
     */
    protected const string SYSTEM_NAME = 'HOYM++';

    /**
     * 服务中心地址（用于版本检测与互联）
     */
    protected const string BASE_URI = 'https://www.hoym.net';

    /**
     * 安装锁文件路径
     */
    protected const string INSTALL_LOCK_FILE = '/resources/install.lock';

    /**
     * 处理请求
     *
     * @param Request  $request
     * @param callable $handler
     *
     * @return Response
     * @throws Exception
     */
    public function process(Request $request, callable $handler): Response
    {
        $this->initLang();

        $isInstalled = file_exists(base_path(self::INSTALL_LOCK_FILE));
        $isInstallController = $this->isInstallController();

        // 未安装 & 非安装控制器 -> 重定向安装
        if (!$isInstalled && !$isInstallController) {
            return redirect(url('install.index'));
        }

        // 已安装 & 是安装控制器 -> 抛出异常阻止重复安装
        if ($isInstalled && $isInstallController) {
            throw new BusinessException(
                message: trans("The system has been installed. To reinstall, delete the resources/install.lock file.")
            );
        }

        // 共享全局视图变量
        View::assign([
            'version'     => self::VERSION,
            'lang'        => session('lang'),
            'system_name' => self::SYSTEM_NAME,
            'base_uri'    => self::BASE_URI
        ]);

        return $handler($request);
    }

    /**
     * 初始化语言环境
     *
     * @return void
     * @throws Exception
     */
    protected function initLang(): void
    {
        $language = session('lang') ?: getPreferredLanguage();
        locale(str_replace('-', '_', $language));
    }

    /**
     * 判断是否为安装控制器
     *
     * @return bool
     */
    protected function isInstallController(): bool
    {
        $controller = request()->controller ?? '';

        return str_contains($controller, '\\Install\\');
    }
}