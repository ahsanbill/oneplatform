<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class Agency
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
        if(!Auth::check()){

            return redirect(route('login'));
        }

        $user = Auth::user();

        if($user->is_buyer_only){

            if ($routeName = $request->route()->getName() == 'agency.dashboard' || $routeName = $request->route()->getName() == 'agency.dashboard.tab') {
                return $next($request);
            } else if ($request->isMethod('GET')){
                return redirect(route('agency.dashboard'));
            } else {
                return $next($request);
            }
        }

        if($user->profile->basic_setup != 1){

            $data = ['id' => $user->id, 'name' => $user->name, 'firstName' => '', 'lastName' => '', 'email' => $user->email, 'contact' => $user->contact_number];
            Session::put('register.data', $data);
            return redirect(route('profile.simple.setup'));
        }

        return $next($request);
    }
}
