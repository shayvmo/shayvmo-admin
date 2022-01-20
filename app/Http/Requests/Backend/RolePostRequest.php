<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class RolePostRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'=>[
                'required',
                'string',
                'chinese',
                'max:50',
            ],
            'title'=>[
                'required',
                'string',
                'max:25',
            ],
            'desc'=>[
                'sometimes',
                'string',
                'max:80',
                'nullable'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '角色标识'
        ];
    }

    public function fillData()
    {
        return [
            'name' => $this->input('name'),
            'title' => $this->input('title'),
            'desc' => $this->input('desc'),
        ];
    }
}
