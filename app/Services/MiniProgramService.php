<?php

namespace App\Services;

use EasyWeChat\Factory;

class MiniProgramService
{
    private $app;

    public function __construct()
    {
//        $this->app = app('wechat.mini_program');
        $config = config('wechat.mini_program.default');
        $this->app = Factory::miniProgram($config);
    }

    public function code2Session(string $code)
    {
        return $this->app->auth->session($code);
    }

    public function decryptData(string $session, string $iv, string $encryptedData)
    {
        return $this->app->encryptor->decryptData($session, $iv, $encryptedData);
    }
}
