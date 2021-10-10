<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class IsLoggedIn
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
        if(!auth()->check()) {
            return response()->view('redirect');
        }

        // get the current user, add to request
        $request->attributes->add(['user' => auth()->user()]);
        return $next($request);
    }
}
