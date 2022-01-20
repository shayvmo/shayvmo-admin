<?php


namespace App\Exceptions;

/**
 * @ClassName SystemException
 * @Author shayvmo
 * @Date 2020-11-30 18:29 星期一
 * @Version 1.0
 * @Description 系统异常，可用于全局终止
 * @package App\Exceptions
 */
class SystemException extends \Exception
{
    public function render()
    {
        return response($this->getMessage(), 500);
    }
}
