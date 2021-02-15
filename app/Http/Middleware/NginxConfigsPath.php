<?php

namespace App\Http\Middleware;

use App\Services\DomainService;
use Closure;
use Illuminate\Http\Request;

class NginxConfigsPath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('nginx.config_path')) {
            return redirect()
                ->back()
                ->with(
                    DomainService::FLASH_ERROR,
                    trans('nginx.noEnvConfigPath')
                );
        }

        return $next($request);
    }
}
