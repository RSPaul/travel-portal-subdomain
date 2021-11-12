<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {               
                $user = User::find(Auth::user()->id);
                if (Auth::user()->role == "admin") {
                    return redirect('/admin');
                } else if (Auth::user()->role == "user") {
                    if($user->password_changed) {
                        return $next($request);
                    } else {
                        return \Redirect::to('/user/password/change');
                    }
                } else if (Auth::user()->role == "agent") {
                    if($user->password_changed) {
                        return \Redirect::to('/agent/dashboard');
                    } else {
                        return \Redirect::to('/user/password/change');
                    }
                } else {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
