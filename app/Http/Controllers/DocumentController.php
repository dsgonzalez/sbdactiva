<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Document;
use Illuminate\Support\Collection;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docs = Document::paginate(5);
        return view('admin.documents.home',compact('docs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('role', '=', 'client')->lists('fullname','id');
        $users->push('-- Selecciona un cliente --');

        $types=Type::lists('name','id');

        $types->push('-- Selecciona un documento --');

        return view('admin.documents.create',compact('users','types'));
    }

    public function storage(Request $request,$id){

        //dd($request->file->getError());
        $doc=Document::find($id);
        /*compruevo que hay un archivo selecionado*/
        if(empty($request->file)){
            \Session::flash('message','Debes selecionar un archivo');
            return view('admin.documents.file',compact('doc'));
        }
        /*compruevo que no haya algun error*/
        if($request->file->getError() == 1){
            \Session::flash('message','Hay algun error con el archivo');
            return view('admin.documents.file',compact('doc'));
        }


        $file = $request->file('file');
        /*nombre archivo*/
        $nombre = $file->getClientOriginalName();
        $folder=$doc->name;

        $exists=Document::fileExists($file);
        if($exists){
            //retornar vista subir fixero con mensaje error
            \Session::flash('message','El documento ya existe');
            //$option='danger';
            //return redirect()->route('file',[$doc]);
            return view('admin.documents.file',compact('doc'));
        }else {
            \Session::flash('message','El documento se subio correctamente');
            \Storage::disk('local')->put($nombre, \File::get($file));
            //$option='success';
            //Metodo que me da la extension del fichero
            $extension=Document::getExtensionFile($nombre);

            //Crea un id con el id del usuario
            // y la fecha que se creo el fichero
            $docId=Document::createDocumentId($doc->user_id,$extension);

            /*Se mueve el fichero y se renombra*/
            \Storage::move($nombre,'/image/'.$folder.'/'.$docId);
            $doc->name=$nombre;
            $doc->real_name=$docId;
            //$doc->url='image/cat/'.$docId;
            $doc->url='image/'.$folder.'/'.$docId;

            $doc->save();

            \Session::flash('message','El archivo fue subido correctamente');


            return redirect()->route('home-doc');
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
            'user_id' =>'required',
            'type_id' =>'required'
        ]);
        /*compruevo que no sean los valores por defecto*/
        if($request->user_id !=58 && $request->type_id !=3){
            $doc=new Document($request->all());
            $doc->save();
            return view('admin.documents.file',compact('doc'));
        }
        \Session::flash('message','Debes seleccionar un campo');

        return redirect()->route('create-doc');
    }



    /*Provar de pasar la categoria y canviar la ruta en config/filesystems*/
    public function download($id)
    {
        $doc=Document::find($id);

        $public_path=public_path();
        //verificamos si el archivo existe y lo retornamos
        if (\Storage::exists($doc->url))
        {
            $url=$public_path.'\\'.$doc->url;
            return response()->download($url);
        }
        //si no se encuentra lanzamos un error 404.
        abort(404);
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
        /*$doc=Document::findOrFail($id);

        $users = User::where('role', '=', 'client')->lists('fullname','id');
        $users->push('-- Selecciona un cliente --');

        $types=Type::lists('name','id');

        $types->push('-- Selecciona un documento --');*/

        /*consigo la ciudad del usuario*/
        /*$user_id=$doc->user_id;

        $type_id=$doc->type_id;

        return view('admin.documents.edit',compact('doc','users','types','user_id','type_id'));*/
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
       /* $doc=Document::find($id);

        $doc->fill($request->all());

        $doc->save();

        \Session::flash('message','El document fue modificado');

        return redirect()->route('home-doc');*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doc=Document::find($id);
        /*elimino el fichero*/
        \Storage::delete($doc->url);

        Document::destroy($id);

        \Session::flash('message','El documento fue eliminado');

        return redirect()->route('home-doc');
    }
}
