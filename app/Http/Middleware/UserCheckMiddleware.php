<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class UserCheckMiddleware
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
            if ($type == 'admin') {
                return $next($request);
            }elseif($type == 'user' || $type == 'agent') {
                //return \Redirect::to('login');
                $user = User::find(Auth::user()->id);
                if($user->password_changed) {
                    return $next($request);
                } else {
                    return \Redirect::to('/user/password/change');
                }
            }
            return \Redirect::to('login');
        endif;
        return \Redirect::to('login');

        // if (Auth::user()->role == "admin") {
        //     return redirect('/admin');
        // } else if (Auth::user()->role == "user") {
        //    if(Auth::user()->password_changed) {
        //         echo "changed";
        //     } else {
        //         echo "not changed";
        //     }
        //     die();
        //     return redirect('/user/profile');
        // } else {
        //     return redirect(RouteServiceProvider::HOME);
        // }
        // return \Redirect::to('login');
    }

}
