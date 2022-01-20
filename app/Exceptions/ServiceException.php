<?php


namespace App\Exceptions;

use App\Traits\ResponseTrait;

/**
 * @ClassName ServiceException
 * @Author shayvmo
 * @Date 2020-11-30 18:28 星期一
 * @Version 1.0
 * @Description 服务异常，用于全局异常抛出
 * @package App\Exceptions
 */
class ServiceException extends \Exception
{
    use ResponseTrait;

    public function render()
    {
        return $this->error($this->getMessage());
    }
}
