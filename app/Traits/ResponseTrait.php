<?php


namespace App\Traits;


trait ResponseTrait
{
    protected function success(string $message = '操作成功', array $data = [])
    {
        return $this->response(config('my.result_code.success',0), $message, $data);
    }

    protected function successData(array $data = [])
    {
        return $this->success('操作成功', $data);
    }

    protected function error(string $message = '操作失败', array $data = [])
    {
        return $this->response(config('my.result_code.fail',1), $message, $data);
    }

    protected function notAuth(string $message, array $data = [])
    {
        return $this->response(config('my.result_code.login_expire',-1), $message, $data);
    }

    protected function notAllow(string $message, array $data = [])
    {
        return $this->response(config('my.result_code.forbidden',-2), $message, $data);
    }

    private function response(int $code, string $message, array $data, int $statusCode = 200)
    {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data,
            'timestamp' => time(),
        ], $statusCode);
    }
}
