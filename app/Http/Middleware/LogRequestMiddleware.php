<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        \App\Models\Request::create([
            "date_and_time" => Carbon::now()->toDateTimeString(),
            "ip" => $request->ip(),
            "user_agent" => $request->header('user-agent'),
            "url" => $request->fullUrl()
        ]);


        return $next($request);
    }
}
