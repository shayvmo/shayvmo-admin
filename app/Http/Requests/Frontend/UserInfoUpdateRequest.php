<?php


namespace App\Http\Requests\Frontend;


use App\Http\Requests\BaseRequest;

class UserInfoUpdateRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'encryptedData' => [
                'required',
                'string',
            ],
            'iv' => [
                'required',
                'string',
            ],
            'rawData' => [
                'required',
                'string',
            ],
            'signature' => [
                'required',
                'string',
            ],
        ];
    }

    public function fillData()
    {
        return [
            'encryptedData' => $this->post('encryptedData'),
            'iv' => $this->post('iv'),
            'rawData' => $this->post('rawData'),
            'signature' => $this->post('signature'),
        ];
    }
}
