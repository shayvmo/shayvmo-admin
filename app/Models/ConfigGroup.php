<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigGroup extends Model
{
    protected $fillable = [
        'group_key',
        'name',
        'sort',
    ];

    //配置项
    public function configs()
    {
        return $this->hasMany(Config::class,'group_id','id');
    }
}
