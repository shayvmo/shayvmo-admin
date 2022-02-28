<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 请求日志类
 * Class RequestLog
 * @package App\Http\Middleware
 */
class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $params = [
            'admin_id' => $request->user() ? $request->user()->id : 0,
            'id_address' => $request->getClientIp(),
            'url' => $request->fullUrl(),
            'route_name' => $request->route()->getName(),
            'user_agent' => $request->userAgent(),
            'param' => $request->except(['_token','_method']),
            'method' => $request->method(),
        ];
        \App\Services\Base\LogService::addRequestLog($params);
    }
}
