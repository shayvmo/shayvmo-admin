<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * 校验用户权限中间件
 * Class CheckUserAuth
 * @package App\Http\Middleware
 */
class CheckUserAuth
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
        $routeName = $request->route()->getName();
        $permission = Permission::whereRoute($routeName)->first();
        $permissionName = $permission ? $permission->name : $routeName;
        //校验用户权限, swoole监听下，无法使用request->user() 拿到当前登陆用户，fpm可以
        if (!Auth::user()->can($permissionName)) {
            $msg = config('app.debug')?'用户暂无权限: '. $permissionName :'用户暂无权限';
            throw new AccessDeniedHttpException($msg. ' ');
        }
        return $next($request);
    }
}
