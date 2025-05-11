<?php
declare(strict_types=1);

namespace App\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Warrior\RateLimiter\Limiter;

class MailService
{
    /**
     * PHPMailer 实例
     *
     * @var PHPMailer
     */
    private PHPMailer $mailer;

    /**
     * 阿里云邮件推送的 SMTP 服务器地址
     *
     * @var string
     */
    private string $host = 'smtpdm-ap-southeast-1.aliyun.com';

    /**
     * 构造函数
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    /**
     * 发送邮件
     *
     * @param array|string $to          收件人地址，支持字符串或关联数组 ['email' => '姓名']
     * @param string       $subject     邮件主题
     * @param string       $body        邮件正文内容（支持 HTML 格式）
     * @param array        $attachments 附件列表，格式：['文件路径' => '显示文件名']
     * @param array        $cc          抄送地址列表，格式：['cc1@example.com', 'cc2@example.com']
     * @param array        $bcc         密送地址列表，格式：['bcc1@example.com', 'bcc2@example.com']
     * @param array|null   $replyTo     回复地址，格式：['email' => '名称']
     *
     * @return array 发送结果，格式：['success' => true] 或 ['success' => false, 'error' => 错误信息]
     */
    public function send(array|string $to, string $subject, string $body, array $attachments = [], array $cc = [], array $bcc = [], ?array $replyTo = null): array
    {
        Limiter::check($to, 5, 24 * 60 * 60, trans("Each mailbox can send up to 5 verification codes per day"));
        try {
            // 设置收件人
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    $this->mailer->addAddress($email, $name);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // 设置回复地址
            if ($replyTo) {
                $this->mailer->addReplyTo($replyTo['email'], $replyTo['name'] ?? '');
            }

            // 添加抄送地址
            foreach ($cc as $email) {
                $this->mailer->addCC($email);
            }

            // 添加密送地址
            foreach ($bcc as $email) {
                $this->mailer->addBCC($email);
            }

            // 添加附件
            foreach ($attachments as $file => $filename) {
                $this->mailer->addAttachment($file, $filename);
            }

            // 邮件内容设置
            $this->mailer->isHTML(); // 启用 HTML 格式邮件
            $this->mailer->Subject = $subject; // 设置邮件主题
            $this->mailer->Body = $body; // 设置邮件正文

            // 发送邮件
            $this->mailer->send();

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $this->mailer->ErrorInfo];
        }
    }

    /**
     * 配置 PHPMailer 参数
     *
     * 包括 SMTP 服务器、端口、加密方式、身份认证信息等。
     *
     * @return void
     * @throws Exception
     */
    private function setup(): void
    {
        $this->mailer->CharSet = 'UTF-8'; // 设置字符编码，防止中文乱码
        $this->mailer->isSMTP(); // 使用 SMTP 方式发送邮件
        $this->mailer->Host = $this->host; // 阿里云邮件推送的 SMTP 服务器地址
        $this->mailer->Port = 465; // 阿里云推荐的 SSL 加密端口
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // 启用 SMTPS 加密
        $this->mailer->SMTPAuth = true; // 启用 SMTP 身份验证

        $this->mailer->Username = 'no-reply@hoym.net'; // 发件人 SMTP 用户名（完整邮箱地址）
        $this->mailer->Password = 'hoym2025ailA21sXs'; // SMTP 授权码（不是邮箱登录密码）
        $this->mailer->setFrom('no-reply@hoym.net', '网站通知'); // 设置默认发件人地址和名称
        $this->mailer->Sender = 'no-reply@hoym.net'; // 设置退信地址（阿里云要求必须设置）
    }
}