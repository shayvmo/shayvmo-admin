<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;

class CheckInstall
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
        if (!File::exists(storage_path('app/install.lock'))) {
            return redirect(route('install'));
        }
        return $next($request);
    }
}
