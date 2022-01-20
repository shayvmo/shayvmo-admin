<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

/**
 * 设置角色权限请求
 * Class RolePermissionsPostRequest
 * @package App\Http\Requests\Backend
 */
class RolePermissionsPostRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'rules'=>[
                'array',
            ],
        ];
    }

    public function fillData()
    {
        return [
            'rules' => $this->post('rules') ?? []
        ];
    }
}
