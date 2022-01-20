<?php


namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class ConfigPostRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'label'=>[
                'required',
                'string',
                'max:30',
            ],
            'key'=>[
                'required',
                'string',
                'max:30',
            ],
            'val'=>[
                'required',
                'string',
                'max:150',
            ],
            'type'=>[
                'required',
                'integer',
                'in:1,2,3',
            ],
            'group_id'=>[
                'required',
                'integer',
                'min:1',
            ],
            'config_file_key'=>[
                'sometimes',
                'string',
                'nullable',
                'max:30',
            ],
            'tips'=>[
                'sometimes',
                'string',
                'nullable',
                'max:50',
            ],
            'sort'=>[
                'sometimes',
                'integer',
                'min:0'
            ],
        ];
    }

    public function fillData()
    {
        return [
            'group_id' => $this->input('group_id'),
            'label' => $this->input('label'),
            'key' => $this->input('key'),
            'config_file_key' => $this->input('config_file_key') ?: '',
            'val' => $this->input('val'),
            'type' => $this->input('type'),
            'tips' => $this->input('tips') ?: '',
            'sort' => $this->input('sort', 100),
        ];
    }
}
