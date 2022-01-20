<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\PagePost;

/**
 * 管理员列表请求
 * Class AdminListRequest
 * @package App\Http\Requests\Backend
 */
class AdminListRequest extends PagePost
{
    public function rules()
    {
        return array_merge(parent::rules(),[
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
        return array_merge(parent::fillData(), ['keyword' => $this->get('keyword')]);
    }
}
