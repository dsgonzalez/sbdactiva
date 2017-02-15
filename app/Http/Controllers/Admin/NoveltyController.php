<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Novelty;

class NoveltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $novelties = Novelty::paginate(10);
        return view('admin.novelties.home',compact('novelties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.novelties.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=\Auth::user();

        //dd($user->id);

        $this->validate($request, [
            'title' => 'required|max:255',
            'text' => 'required'

        ]);
        $novelty=new Novelty($request->all());
        $novelty->user_id=$user->id;


        $novelty->save();
        \Session::flash('message','La noticia fue creada');

        return redirect()->route('home-nov');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $novelty=Novelty::findOrFail($id);

        return view('admin.novelties.edit',compact('novelty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $novelty=Novelty::find($id);

        $novelty->fill($request->all());

        $novelty->save();

        \Session::flash('message','La noticia se ha modificado');

        return redirect()->route('home-nov');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Novelty::destroy($id);

        \Session::flash('message','La noticia ha sido eliminada');

        return redirect()->route('home-nov');
    }
}
