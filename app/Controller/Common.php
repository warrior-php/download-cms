<?php
declare(strict_types=1);

namespace App\Controller;

use App\Request\Validator;
use support\exception\BusinessException;
use support\Request;
use support\Response;
use Exception;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;

class Common
{
    /**
     * 无需登录的操作列表
     *
     * 控制器中定义的动作名称（如方法名），列在此数组中时，
     * 用户即使未登录也可以访问这些动作。
     *
     * 示例：
     * - login：登录页面或操作
     * - logout：退出操作
     *
     * @var string[]
     */
    protected array $noNeedLogin = ['login', 'captcha'];

    /**
     * 无需鉴权的操作列表
     *
     * 用户虽然需要登录，但不必验证具体权限的操作列表。
     * 通常用于首页仪表盘、公告页等不敏感模块。
     *
     * 示例：
     * - dashboard：系统仪表盘页面
     *
     * @var string[]
     */
    protected array $noNeedAuth = ['logout'];

    /**
     * @param Request $request
     * @param string  $type
     *
     * @return Response
     * @throws Exception
     */
    protected function captcha(Request $request, string $type = 'login'): Response
    {
        $builder = new PhraseBuilder(4, 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ');
        $captcha = new CaptchaBuilder(null, $builder);
        $captcha->build(120);
        $request->session()->set("captcha-$type", strtolower($captcha->getPhrase()));
        $img_content = $captcha->get();
        return response($img_content, 200, ['Content-Type' => 'image/jpeg']);
    }

    /**
     * 通用验证方法
     *
     * @param string $ruleClass 类名（可传简写：User 或完整命名空间）
     * @param array  $data      待验证的数据
     * @param string $scene     场景名称（可选）
     *
     * @return void
     */
    protected function validate(string $ruleClass, array $data, string $scene = ''): void
    {
        // 拼接完整类名（如果没带命名空间）
        if (!str_contains($ruleClass, '\\')) {
            $ruleClass = 'App\\Request\\' . $ruleClass;
        }
        // 检查类是否存在
        if (!class_exists($ruleClass)) {
            throw new BusinessException("Class not found: $ruleClass");
        }
        // 实例化验证器
        $validator = new $ruleClass();
        // 如果提供了场景，则设置
        if ($scene && method_exists($validator, 'scene')) {
            $validator->scene($scene);
        }
        /** @var Validator $validator */
        $validator->validate($data);
    }
}