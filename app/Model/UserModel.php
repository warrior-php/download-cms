<?php

namespace App\Model;

use RuntimeException;
use support\exception\BusinessException;
use support\Log;
use Throwable;

/**
 * @property integer $id                         ID(主键)
 * @property string  $username                   用户名
 * @property string  $nickname                   昵称
 * @property string  $password                   密码（已加密）
 * @property integer $sex                        性别
 * @property string  $avatar                     头像
 * @property string  $email                      邮箱
 * @property string  $mobile                     手机
 * @property int     $level                      等级
 * @property string  $birthday                   生日
 * @property string  $money                      余额
 * @property string  $score                      积分
 * @property string  $last_time                  登录时间
 * @property string  $last_ip                    登录ip
 * @property string  $join_time                  注册时间
 * @property string  $join_ip                    注册ip
 * @property string  $token                      token
 * @property string  $created_at                 创建时间
 * @property string  $updated_at                 更新时间
 * @property int     $role                       角色
 * @property integer $status                     状态 0正常 1禁用
 */
class UserModel extends Model
{
    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 允许批量赋值的字段
     *
     * @var array
     */
    protected $fillable = ['username', 'nickname', 'password', 'email', 'mobile'];

    /**
     * 自动加密密码
     *
     * @param string $value
     */
    public function setPasswordAttribute(string $value): void
    {
        // 防止重复加密
        if ($value && !password_get_info($value)['algo']) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        } else {
            $this->attributes['password'] = $value;
        }
    }


    /**
     * 根据标识符查找用户（自动识别邮箱/ID/用户名）
     *
     * @param string $identifier
     *
     * @return UserModel|null
     */
    public static function findByIdentifier(string $identifier): ?UserModel
    {
        $identifier = trim($identifier);

        if (empty($identifier)) {
            throw new BusinessException(message: trans("Unknown Error"));
        }

        return match (true) {
            filter_var($identifier, FILTER_VALIDATE_EMAIL) => self::where('email', $identifier)->first(),
            default => is_numeric($identifier) ? self::find($identifier) : self::where('username', $identifier)->first(),
        };
    }

    /**
     * Email 是否存在
     *
     * @param string $email
     *
     * @return bool
     */
    public static function hasEmail(string $email): bool
    {
        if (self::where('email', $email)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Username 是否存在
     *
     * @param string $email
     *
     * @return bool
     */
    public static function hasUsername(string $email): bool
    {
        if (self::where('username', $email)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * 创建用户
     *
     * @param array $data ['username', 'email', 'password', 'status' (可选)]
     *
     * @return UserModel
     *
     * @throws BusinessException
     */
    public static function createUser(array $data): UserModel
    {
        self::validateRequiredFields($data, ['username', 'email', 'password']);

        try {
            $user = self::create($data);
            if (!$user->save()) {
                throw new RuntimeException('Save failed');
            }

            return $user;
        } catch (Throwable $e) {
            // 记录错误日志
            Log::error(trans("User creation failed"), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new BusinessException(trans("User creation failed"));
        }
    }
}