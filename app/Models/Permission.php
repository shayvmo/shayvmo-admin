<?php
namespace App\Models;


class Permission extends \Spatie\Permission\Models\Permission
{

    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'pid');
    }
}
