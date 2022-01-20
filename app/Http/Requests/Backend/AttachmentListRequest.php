<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\PagePost;


class AttachmentListRequest extends PagePost
{
    public function rules()
    {
        return array_merge(parent::rules(),[
            'attachment_group_id'=>[
                'sometimes',
                'integer',
                'min:0',
                'nullable',
            ],
            'keyword'=>[
                'sometimes',
                'string',
                'max:30',
                'nullable',
            ],
        ]);
    }

    public function fillData()
    {
        return array_merge(parent::fillData(), [
            'keyword' => $this->get('keyword'),
            'attachment_group_id' => $this->get('attachment_group_id', 0)
        ]);
    }
}
