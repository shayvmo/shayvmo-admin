<?php


namespace App\Http\Requests;


use App\Exceptions\ValidateException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidateException(implode(',', $validator->errors()->all()));
    }

    abstract public function fillData();
}
