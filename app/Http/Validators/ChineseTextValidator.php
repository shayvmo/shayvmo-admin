<?php
/**
 * Author: shayvmo
 * CreateTime: 2020/6/1 15:59
 * Description:
 */

namespace App\Http\Validators;

/**
 * 是否包含中文字
 * Class ChineseTextValidator
 * @package App\Http\Validators
 */
class ChineseTextValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return !check_chinese_text($value);
    }
}
