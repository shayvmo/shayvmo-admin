<?php

/*
 * This file is part of the huaxin/wechat.
 *
 * (c) viphuaxin.com <it@viphuaxin.com>
 */

namespace App\Http\Validators;

/**
 * 手机号
 * Class PhoneValidator
 * @package App\Http\Validators
 */
class PhoneValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return check_mobile($value);
    }
}
