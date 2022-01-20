<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'old_password' => 'required|string|min:5|max:30',
            'new_password' => 'required|string|min:5|max:30|confirmed',
            'new_password_confirmation' => 'required|string|min:5|max:30',
        ];
    }

    public function fillData()
    {
        return [
            'old_password' => $this->post('old_password'),
            'new_password' => $this->post('new_password'),
        ];
    }
}
