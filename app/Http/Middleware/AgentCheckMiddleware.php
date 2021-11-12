<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class AgentCheckMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()):
            $type = Auth::user()->role; 
            if ($type == 'agent') {
                return $next($request);
            }else{
                return \Redirect::to('login');
            }
        endif;
        return \Redirect::to('login');
    }

}
