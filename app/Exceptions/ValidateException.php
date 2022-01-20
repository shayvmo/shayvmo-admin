<?php


namespace App\Exceptions;


use App\Traits\ResponseTrait;

/**
 * @ClassName ValidateException
 * @Author shayvmo
 * @Date 2020-11-30 18:30 星期一
 * @Version 1.0
 * @Description 请求验证异常，全局
 * @package App\Exceptions
 */
class ValidateException extends \Exception
{
    use ResponseTrait;

    public function render()
    {
        return $this->error($this->getMessage());
    }
}
