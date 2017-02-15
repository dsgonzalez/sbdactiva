<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = ['name', 'real_name', 'url', 'user_id', 'type_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    public function getUserDocument()
    {
        return User::where('id',$this->user_id)->first()->name;
    }

    public function type()
    {
        return $this->belongsTo('App\Type', 'type_id');
    }


    public function getTypeDocument()
    {
        return User::where('id',$this->type_id)->first()->name;
    }

    static function saveFile($exists,$nombre,$file){

        if(empty($nombre)){
            \Session::flash('message','Debes selecionar un fichero');
            return redirect()->route('create-doc');
        }

        if(empty($file)){
            \Session::flash('message','Hay un problema con este archivo');
            return redirect()->route('create-doc');
        }
        if($exists){
            // dd($exists);
            //retornar vista subir fixero con mensaje error
            \Session::flash('message','El documento ya existe');
            return redirect()->route('create-doc');
        }else {
            // \Storage::disk('local')->put('/image/storage/' . $nombre, \File::get($file));
            \Session::flash('message','El documento se subio correcto');
            \Storage::disk('local')->put($nombre, \File::get($file));
            return redirect()->route('create-doc');
        }
    }

    static function fileExists($file){
        $nombre = $file->getClientOriginalName();
        //dd($nombre);
        $typeFile=$file->getMimeType();
        switch($typeFile){
            case 'application/pdf':
                $exists = \Storage::disk('local')->exists($nombre);
                //dd($exists);
                break;
            case 'image/png':
               // dd(public_path());
                $exists = \Storage::disk('local')->exists($nombre);
                break;
            default:abort(404);
            /*default:\Session::flash('message','Solo se puede subir archivos .pdf o .png');

                    return redirect()->route('create-doc');*/
        }
        return $exists;

    }


    static function getExtensionFile($file){
        $arr=explode('.',$file);
        $extension=$arr[1];
        return $extension;

    }


    static function createDocumentId($userId,$ext){
        //crear imagen y guardarla en bbdd
        $fecha= \Carbon\Carbon::now();
        $fecha1 = $fecha->format('d-m-Y');
        $fecha2 =$fecha->toTimeString();
        $arr1=explode("-",$fecha1);
        $arr2=explode(":",$fecha2);
        $imageId=$arr1[0].$arr1[1].$arr1[2].$arr2[0].$arr2[1].$arr2[2];
        $imageId=$userId.'_'.$imageId.'.'.$ext;
        return $imageId;

    }
}
