<?php


namespace App\Services\Base;


use App\Models\RequestLog;

class LogService
{
    /**
     * 请求日志
     * @param array $params
     */
    public static function addRequestLog(array $params): void
    {
        $demo_params = [
            'admin_id',
            'id_address',
            'url',
            'route_name',
            'user_agent',
            'param',
            'method',
        ];
        RequestLog::query()->create($params);
    }
}
