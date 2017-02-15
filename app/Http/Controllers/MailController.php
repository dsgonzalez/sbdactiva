<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:255',
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'town' => 'required|max:255',
            'email' => 'required|email|max:255',
            'comments' => 'required|max:255',
            'captcha' => 'required|captcha',

        ]);

        $data = $request->all();


        //se envia el array y la vista lo recibe en llaves individuales {{ $email }} , {{ $subject }}...
        \Mail::send('mail', $data, function($message) use ($request)
        {
            //remitente
            $message->from($request->email, $request->name);

            //asunto
            $message->subject($request->subject);

            //receptor
            $message->to(env('CONTACT_MAIL'), env('CONTACT_NAME'));



        });
        //return \View::make('success');
        return view('mail-success');
       // dd($request);
    }


}
