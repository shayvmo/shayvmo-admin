<?php
namespace App\Models;


/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property string $title
 * @property string $desc
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Role extends \Spatie\Permission\Models\Role
{
    protected $fillable = [
        'name',
        'title',
        'desc',
        'guard_name',
    ];
}
