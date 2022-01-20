<?php
/**
 * laravel-backend
 *
 * @ClassName PermissionPostRequest
 * @Author Eric
 * @Date 2021-06-23 16:02 星期三
 * @Version 1.0
 * @Description
 */


namespace App\Http\Requests\Backend;

use App\Constants\BackendConstant;
use App\Http\Requests\BaseRequest;

class PermissionPostRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'pid' => [
                'required',
                'integer',
                'min:0'
            ],
            'title' => [
                'required',
                'string',
                'max:100'
            ],
            'icon' => [
                'sometimes',
                'string',
                'max:50',
                'nullable',
            ],
            'type' => [
                'required',
                'integer',
                'in: 1,2,3'
            ],
            'is_menu' => [
                'required',
                'integer',
                'in: 0,1'
            ],
            'route' => [
                'sometimes',
                'string',
                'max:50',
                'nullable',
            ],
            'sort' => [
                'integer',
                'min:0'
            ],
        ];
    }

    public function fillData()
    {
        return [
            'pid' => $this->input('pid'),
            'name' => $this->input('name'),
            'title' => $this->input('title') ?? '',
            'icon' => $this->input('icon') ?? '',
            'type' => $this->input('type'),
            'is_menu' => $this->input('is_menu'),
            'route' => $this->input('route') ?? '',
            'path' => '',
            'sort' => $this->input('sort') ?? 100,
        ];
    }
}
