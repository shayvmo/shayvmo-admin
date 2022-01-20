<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Hash;

/**
 * hash 验证
 * Class HashValidator
 * @package App\Http\Validators
 */
class HashValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return Hash::check($value, $parameters[0]);
    }
}
