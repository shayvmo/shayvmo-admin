<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $group_id  组ID
 * @property string $label  配置项名称
 * @property string $key  配置项字段
 * @property string $config_file_key  系统配置文件对应key值
 * @property string $val  配置项值
 * @property int $type  1 radio 2 text 3 textarea 4 radio
 * @property string $options  选项值
 * @property string $tips  输入提示
 * @property int $sort  排序
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Config extends Model
{
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'group_id',
        'label',
        'key',
        'config_file_key',
        'val',
        'type',
        'options',
        'tips',
        'sort',
    ];

    private const TYPE_TEXT = [
        1 => 'switch',
        2 => 'text',
        3 => 'textarea',
        4 => 'radio',
    ];

    public function getTypeAttribute($value)
    {
        return self::TYPE_TEXT[$value] ?? $value;
    }
}
