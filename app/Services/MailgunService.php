<?php
declare(strict_types=1);

namespace App\Services;

use Mailgun\Mailgun;
use Psr\Http\Client\ClientExceptionInterface;

class MailgunService
{
    /**
     * @var Mailgun
     */
    private Mailgun $client;

    /**
     * Mailgun 发送域名
     */
    private string $domain;

    /**
     * 构造函数，初始化 Mailgun 客户端
     *
     * @param string|null $apiKey   Mailgun API Key
     * @param string|null $domain   Mailgun 域名
     * @param string|null $endpoint 自定义 endpoint（可选）
     */
    public function __construct(?string $apiKey = null, ?string $domain = null, ?string $endpoint = null)
    {
        $apiKey = $apiKey ?? getenv('MAILGUN_API_KEY') ?? 'your-default-api-key';
        $domain = $domain ?? getenv('MAILGUN_DOMAIN') ?? 'your-domain.mailgun.org';
        $endpoint = $endpoint ?? getenv('MAILGUN_API_ENDPOINT'); // 可为空

        $this->client = Mailgun::create($apiKey, $endpoint ?: 'https://api.mailgun.net');
        $this->domain = $domain;
    }

    /**
     * 发送邮件
     *
     * @param string      $to      收件人邮箱（可包含名称，如 "Li Chen <li@abc.com>"）
     * @param string      $subject 邮件标题
     * @param string      $text    邮件正文（纯文本）
     * @param string|null $from    发件人（默认使用 postmaster@domain）
     *
     * @return string|null 消息ID 或 null
     * @throws ClientExceptionInterface
     */
    public function send(string $to, string $subject, string $text, ?string $from = null): ?string
    {
        $from ??= sprintf('Mailgun Sandbox <postmaster@%s>', $this->domain);

        $response = $this->client->messages()->send($this->domain, [
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'text'    => $text,
        ]);

        return $response->getId(); // 返回消息ID（例如 '<202305...@mailgun.org>'）
    }
}