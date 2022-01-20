<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,HasRoles, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'openid',
        'password',
        'api_token',
        'session_key',
        'avatar',
        'nickname',
        'mobile',
        'gender',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(Article::class, 'article_stars', 'user_id', 'article_id')
            ->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(Article::class, 'article_collections', 'user_id', 'article_id')
            ->withTimestamps();
    }

}
