<?php

namespace App\Http\Validators;

/**
 * 用户名
 * Class UsernameValidator
 * @package App\Http\Validators
 */
class UsernameValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[a-zA-Z]+[a-zA-Z0-9\-]+$/', $value);
    }
}
