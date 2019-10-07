<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Session;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \Closure $next
     * @param string[] ...$guards
     * @return mixed
     */
    protected $guards = [];
   /* public function redirectTo($request)
    {
        return $request->session()->has('logged');
        if($request->session()->has('logged')){
            $logged = $request->session()->get('logged');
            if($logged == false){
                return '/auth';
            }
        }else{
            return '/auth';
        }
    }
*/
    public function handle($request, Closure $next, ...$guards){
        if($request->session()->has('logged')){
            $logged = $request->session()->get('logged');
            if($logged == false){
                return '/auth';
            }
        }else{
            return '/auth';
        }
    }
}
    

