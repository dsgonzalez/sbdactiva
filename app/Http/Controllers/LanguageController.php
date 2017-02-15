<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Novelty;

class LanguageController extends Controller
{
    /**
     * Muestra las novedades en la parte publica.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $novelties=Novelty::orderBy('created_at','desc')->paginate(2);
        return view('novelties',compact('novelties'));
    }


    /**
     * Canvia el idioma en lo guarda en session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lang= \Session::put('locale',$request->language);
        \App::setLocale($lang);

        return redirect()->route('/');
    }

    public function home(){

        $novelty=Novelty::orderBy('created_at','desc')->first();
        return view('home',compact('novelty'));

    }
}
