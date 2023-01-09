<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformData
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
        collect($request->all())->each(function($item, $key) use($request) {
            if (is_string($item)) {
                $request[$key] = strtolower($item);
            }

            if (is_array($item)) {
                $request[$key] = json_encode($item);
            }
        });
        
        return $next($request);
    }
}
