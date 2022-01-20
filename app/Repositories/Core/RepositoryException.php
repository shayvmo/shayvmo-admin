<?php


namespace App\Repositories\Core;


use App\Traits\ResponseTrait;

class RepositoryException extends \Exception
{
    use ResponseTrait;

    public function render()
    {
        return $this->error($this->getMessage());
    }
}
