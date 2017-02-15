<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Town;
use App\Province;
use Illuminate\Support\Facades\Input;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users=User::paginate()->where();

        $users = User::where('role', '=', 'client')->paginate(10);
        return view('admin.users.home',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces=Province::lists('name','id');
        /*añado una posicion para la muestra por defecto*/
        $provinces->push('-- Seleciona una Provincia --');

        return view('admin.users.create',compact('provinces'));
    }

    /*metodo ejecuta peticion Ajax*/
    public function getTowns(Request $request,$id){

        if($request->ajax()){
            $towns=Town::where('province_id','=',$id)->get();
            return response()->json($towns);

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'telephone_number' => 'required|numeric|min:9',
            //'province_id' => 'required|max:255',
            'town_id' => 'required|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        $user=new User($request->all());

        //$user->password=bcrypt($request->password);
        $user->fullname=$user->getFullName();
        $user->save();
        \Session::flash('message','El usuario fue creado');

        return redirect()->route('home');
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
        $user=User::findOrFail($id);
        /*consigo la ciudad del usuario*/
        $town=Town::findOrFail($user->town_id);
        /*consigo la provincia del usuario*/
        $provinces=Province::lists('name','id');
        /*consigo todas las ciudades de la provincia de residencia del usuario*/
        $towns=Town::where('province_id','=',$town->province_id)->lists('name','id');

        return view('admin.users.edit',compact('user','provinces','town','towns'));
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
        $user=User::find($id);

        $user->fill($request->all());

        $user->save();

        \Session::flash('message','El usuario fue modificado');

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);

        \Session::flash('message','El usuario fue eliminado');

        return redirect()->route('home');
    }
}
