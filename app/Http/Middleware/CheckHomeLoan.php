<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\HomeLoan;
use Illuminate\Http\Request;

class CheckHomeLoan
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
        $check = HomeLoan::all();

        if(!$check)
            return response()->view('check');

        return $next($request);
    }
}
