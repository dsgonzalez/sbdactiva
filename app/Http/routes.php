<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*RUTAS PUBLICAS*/
Route::group(['middleware'=>'locale'], function(){


    Route::get('/', ['as'=>'/', 'uses' => 'LanguageController@home']);


    // Password reset link request routes...
    /*Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');*/

    // Password reset routes...
   /* Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');*/


    // Authentication routes...
    Route::get('inicio-de-sesion',['as'=>'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('inicio-de-sesion',['as'=>'login', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('fin-de-sesion',['as'=>'logout', 'uses' => 'Auth\AuthController@getLogout']);

    //Enviar formulario contacto
    Route::get('contacto',['as' => 'contact','uses' => 'MailController@index']);
    Route::post('enviar-formulario',['as'=>'send','uses'=>'MailController@send']);

    Route::get('informacion-cookies',['as'=>'info-cookies', 'uses' => function(){

        return view('advertisements.cookies');
    }]);

    Route::get('aviso-legal',['as'=>'info-legal', 'uses' => function(){

        return view('advertisements.legal-advise');
    }]);

    Route::get('empresa',['as'=>'company', 'uses' => function(){

        return view('company');
    }]);

    Route::get('emprendedores',['as'=>'entrepeneur','uses'=>function(){
        return view('entrepeneur');
    }]);

    Route::get('contabilidad',['as'=>'accounting','uses'=>function(){
        return view('accounting');
    }]);

    Route::get('impuestos',['as'=>'tax','uses'=>function(){
        return view('tax');
    }]);

    Route::get('laboral',['as'=>'labour','uses'=>function(){
        return view('labour');
    }]);

    /*Route::get('juridico',['as'=>'legal-advice','uses'=>function(){
        return view('legal-advice');
    }]);*/

    Route::get('fincas',['as'=>'estate','uses'=>function(){
        return view('estate');
    }]);

    Route::get('vehiculos',['as'=>'vehicle','uses'=>function(){
        return view('vehicle');
    }]);

    Route::get('noticias',['as'=>'public-nov','uses'=>'LanguageController@index']);

    /*Idiomas*/
    Route::post('idioma',['as'=>'language','uses'=>'LanguageController@store' ]);



/*RUTAS PRIVADAS*/


Route::group(['middleware'=>'auth'], function()
{



    Route::group(['prefix'=>'administrador','middleware'=>'admin'], function()
    {
        /*Administrador*/

        /*Users Admin*/
        Route::get('inicio',['as'=>'home', 'uses' => 'Admin\UserController@index']);
        Route::get('nuevo-usuario', ['as'=>'create', 'uses' => 'Admin\UserController@create']);
        Route::get('towns/{id}','Admin\UserController@getTowns');
        Route::post('nuevo-usuario', ['as'=>'store', 'uses' => 'Admin\UserController@store' ]);
        Route::get('usuario/{id}',['as'=>'edit', 'uses' => 'Admin\UserController@edit' ]);
        Route::put('usuario/{id}',['as'=>'update', 'uses' => 'Admin\UserController@update' ]);
        Route::delete('usuario/{id}',['as'=>'destroy', 'uses' => 'Admin\UserController@destroy' ]);

        /*Documents Admin*/
        Route::get('documentos',['as'=>'home-doc', 'uses' => 'DocumentController@index']);
        Route::get('nuevo-documento', ['as'=>'create-doc', 'uses' => 'DocumentController@create']);
        Route::post('nuevo-documento', ['as'=>'store-doc', 'uses' => 'DocumentController@store' ]);
        Route::post('nuevo-file/{id}', ['as'=>'save-file', 'uses' => 'DocumentController@storage' ]);
        Route::delete('documento/{id}',['as'=>'destroy-doc', 'uses' => 'DocumentController@destroy' ]);

        /*News Admin*/
        Route::get('noticias',['as'=>'home-nov', 'uses' => 'Admin\NoveltyController@index']);
        Route::get('nueva-noticia', ['as'=>'create-nov', 'uses' => 'Admin\NoveltyController@create']);
        Route::post('nueva-noticia', ['as'=>'store-nov', 'uses' => 'Admin\NoveltyController@store' ]);
        Route::get('noticia/{id}',['as'=>'edit-nov', 'uses' => 'Admin\NoveltyController@edit' ]);
        Route::put('noticia/{id}',['as'=>'update-nov', 'uses' => 'Admin\NoveltyController@update' ]);
        Route::delete('noticia/{id}',['as'=>'destroy-nov', 'uses' => 'Admin\NoveltyController@destroy' ]);

    });

    /*Descargar PDF*/
    Route::get('descarga/{id}', ['as'=>'storage', 'uses' => 'DocumentController@download' ]);

    Route::group(['prefix'=>'usuario'], function()
    {
        /*Usuario User*/
        Route::get('inicio',['as'=>'home-user', 'uses' => 'Client\UserController@index']);


    });


});

});