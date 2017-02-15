<?php

namespace App\Http\Middleware;

use Closure;
use App;

class Locale
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


        /*$language = \Session::get('language', \Config::get('app.locale'));
        $a=\Session::get('locale');
        dd($a);
        \App::setLocale($a);*/

        $lang= \Session::get('locale',function(){
            return 'es';
        });
        App::setLocale($lang);

        return $next($request);
    }
}
