<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EmailException;
use CURLFile;
use support\Log;
use Throwable;
use Webman\Http\UploadFile;

/**
 * SubMailService
 * 赛邮(SubMail)邮件服务封装类
 */
class SubMailService
{
    /**
     * 服务配置数组
     *
     * @var array{
     *     appid: string,
     *     appkey: string,
     *     api: array{timestamp: string, send: string},
     *     default_from: string
     * }
     */
    private array $config;

    public function __construct()
    {
        // 加载插件配置
        $this->config = config('plugin.email.app');
        // 验证必要配置项
        $this->validateConfig();
    }


    /**
     * 发送邮件
     *
     * @param array|string $to      收件人地址，支持字符串或数组形式
     * @param string       $subject 邮件主题
     * @param string       $html    HTML邮件内容
     * @param array        $options 可选参数 [
     *                              'from' => '发件人地址',
     *                              'attachments' => '附件路径或UploadFile对象'
     *                              ]
     *
     * @return array API响应数据
     * @throws EmailException 发送失败时抛出
     *
     * @example
     * $service->send('to@example.com', '主题', '<p>内容</p>', [
     *     'from' => 'noreply@example.com',
     *     'attachments' => '/path/to/file.pdf'
     * ]);
     */
    public function send(array|string $to, string $subject, string $html, array $options = []): array
    {
        try {
            // 获取API时间戳
            $timestamp = $this->getTimestamp();
            // 构建请求数据
            $postData = $this->buildRequestData($to, $subject, $html, $options, $timestamp);
            // 调用发送接口
            $response = $this->callApi($this->config['api']['send'], $postData);

            // 记录成功日志
            Log::debug('邮件发送成功', ['response' => $response]);
            return json_decode($response, true);
        } catch (Throwable $e) {
            // 记录错误日志
            Log::error('邮件发送失败', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new EmailException($e->getMessage(), EmailException::API_FAILED);
        }
    }

    /**
     * 获取API服务器时间戳
     *
     * @return int 时间戳(秒级)
     * @throws EmailException 当API请求失败时抛出
     */
    private function getTimestamp(): int
    {
        $response = $this->callApi($this->config['api']['timestamp']);
        $data = json_decode($response, true);
        // 失败时使用本地时间戳作为降级方案
        return $data['timestamp'] ?? time();
    }

    /**
     * 构建API请求数据
     *
     * @param array|string $to        收件人
     * @param string       $subject   主题
     * @param string       $html      内容
     * @param array        $options   附加选项
     * @param string       $timestamp 时间戳
     *
     * @return array 完整请求数据
     */
    private function buildRequestData(array|string $to, string $subject, string $html, array $options, string $timestamp): array
    {
        $data = [
            'appid'        => $this->config['appid'],
            'subject'      => $subject,
            'from'         => $options['from'] ?? $this->config['default_from'],
            'to'           => is_array($to) ? implode(',', $to) : $to,
            'html'         => $html,
            'sign_version' => 2, // 固定签名版本
            'sign_type'    => 'md5', // 固定签名算法
            'timestamp'    => $timestamp, // API时间戳
        ];

        // 处理附件(如果存在)
        if (!empty($options['attachments'])) {
            $data['attachments'] = $this->processAttachments($options['attachments']);
        }

        // 生成请求签名
        $data['signature'] = $this->generateSignature($data);

        return $data;
    }

    /**
     * 处理邮件附件
     *
     * @param string|UploadFile $attachments 附件数据
     *
     * @return CURLFile 标准化附件对象
     * @throws EmailException 当附件类型无效时抛出
     *
     * @note 支持两种附件格式：
     * 1. 字符串路径：'/var/www/uploads/file.pdf'
     * 2. Webman的UploadFile对象
     */
    private function processAttachments(string|UploadFile $attachments): CURLFile
    {
        // 处理字符串路径附件
        if (is_string($attachments)) {
            if (!file_exists($attachments)) {
                throw new EmailException('附件文件不存在', EmailException::INVALID_ATTACHMENT);
            }
            return curl_file_create($attachments);
        }

        // 处理Webman上传文件对象
        if ($attachments instanceof UploadFile) {
            return curl_file_create(
                $attachments->getPathname(), // 临时文件路径
                null,                       // 自动检测MIME类型
                $attachments->getUploadName() // 原始文件名
            );
        }

        throw new EmailException('附件类型必须为文件路径或UploadFile对象', EmailException::INVALID_ATTACHMENT);
    }

    /**
     * 生成API请求签名
     *
     * @param array $data 请求数据
     *
     * @return string MD5签名
     *
     * @note 签名规则：
     * 1. 移除附件和html字段
     * 2. 按键名排序
     * 3. 拼接为key=value&格式字符串
     * 4. 使用appid+appkey+参数字符串+appid+appkey生成MD5
     */
    private function generateSignature(array $data): string
    {
        $temp = $data;
        // 排除不参与签名的字段
        unset($temp['attachments'], $temp['html']);
        // 按键名升序排序
        ksort($temp);

        // 构建参数字符串
        $tempStr = http_build_query($temp, '', '&');
        // 按赛邮规则生成签名
        return md5($this->config['appid'] . $this->config['appkey'] . $tempStr . $this->config['appid'] . $this->config['appkey']);
    }

    /**
     * 调用赛邮API接口
     *
     * @param string $url      API地址
     * @param array  $postData POST数据
     *
     * @return string 原始响应数据
     * @throws EmailException 当网络请求失败时抛出
     *
     * @note 统一设置：
     * - 10秒超时
     * - 禁用SSL验证(生产环境应配置CA证书)
     */
    private function callApi(string $url, array $postData = []): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,    // 返回响应内容
            CURLOPT_POST           => !empty($postData), // 自动设置POST方法
            CURLOPT_POSTFIELDS     => $postData,   // 请求数据
            CURLOPT_SSL_VERIFYPEER => false,   // 禁用SSL验证(开发环境)
            CURLOPT_TIMEOUT        => 10              // 超时时间(秒)
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new EmailException("API请求失败: $error", EmailException::API_FAILED);
        }
        curl_close($ch);

        return $response;
    }

    /**
     * 验证必要配置
     *
     * @throws EmailException 当appid或appkey未配置时抛出
     */
    private function validateConfig(): void
    {
        if (empty($this->config['appid']) || empty($this->config['appkey'])) {
            throw new EmailException('赛邮配置不完整，请检查appid和appkey', EmailException::INVALID_CONFIG);
        }
    }
}