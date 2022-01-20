<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

/**
 * 新增、编辑管理员请求
 * Class AdminPostRequest
 * @package App\Http\Requests\Backend
 */
class AdminPostRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'nickname'=>[
                'required',
                'string',
                'max:15',
            ],
            'status'=>[
                'required',
                'integer',
                'in:0,1'
            ],
            'email'=>[
                'sometimes',
                'email',
                'nullable',
            ],
            'mobile'=>[
                'sometimes',
                'phone',
                'nullable',
            ],
            'roles'=>[
                'sometimes',
                'array',
                'nullable',
            ],
        ];
    }

    public function fillData()
    {
        return [
            'username' => $this->input('username'),
            'nickname' => $this->input('nickname'),
            'status' => $this->input('status') ?: 0,
            'email' => $this->input('email') ?: '',
            'mobile' => $this->input('mobile') ?: '',
            'roles' => $this->input('roles') ?: [],
        ];
    }
}
