<?php


namespace App\Models;


class AttachmentGroup extends BaseModel
{
    protected $fillable = ['title'];

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'group_id', 'id');
    }
}
