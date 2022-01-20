<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $username  登录账号
 * @property string $password  密码
 * @property string $nickname  呢称
 * @property string $email  邮箱
 * @property int $status  1启用 0停用
 * @property string $mobile  手机号
 * @property string $last_login_ip  上次登录IP
 * @property string $last_login_at  上次登录时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'username',
        'password',
        'nickname',
        'email',
        'status',
        'mobile',
        'last_login_ip',
        'last_login_at',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
