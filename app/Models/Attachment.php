<?php


namespace App\Models;


class Attachment extends BaseModel
{
    protected $fillable = [
        'group_id',
        'storage_type',
        'name',
        'path',
        'md5_file',
        'size',
        'mime_type',
    ];
}
