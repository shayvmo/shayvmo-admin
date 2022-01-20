<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'admin_id',
        'id_address',
        'url',
        'route_name',
        'user_agent',
        'param',
        'method',
    ];


    public function setParamAttribute($value)
    {
        $this->attributes['param'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
