<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertFormDataToBody
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
        if ($request->isMethod('put') && $request->header('Content-Type') === 'multipart/form-data') {
            $request->request->add($request->all());
        }

        return $next($request);
    }
}
