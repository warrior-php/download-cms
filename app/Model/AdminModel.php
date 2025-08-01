<?php
declare(strict_types=1);

namespace App\Model;

use support\Model;

/**
 * @property integer $id         ID(主键)
 * @property string  $username   用户名
 * @property string  $nickname   昵称
 * @property string  $password   密码
 * @property string  $avatar     头像
 * @property string  $email      邮箱
 * @property string  $mobile     手机
 * @property string  $created_at 创建时间
 * @property string  $updated_at 更新时间
 * @property string  $login_at   登录时间
 * @property string  $roles      角色
 * @property integer $status     状态 0正常 1禁用
 */
class AdminModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}