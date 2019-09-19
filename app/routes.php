<?php

//Verifica si el usuario esta autenticado y lo envia al login o la principal
Route::filter('auth', function(){
    if (Auth::usuario()->guest()) return Redirect::route("login");
});


Route::get('/',
    ['as'=>"inicio",
    'uses'=> function() {
        if(Auth::usuario()->check())
        {
            return View::make("layouts.inicio");
        }
        return Redirect::route("login");
    }]);

Route::any('login', [
    "as"=>"login",
    "uses"=>"UsuarioController@login"
]);

Route::get('logout',
    ['as'=>"logout",
     'uses'=>function(){
         Auth::usuario()->logout();
         return Redirect::route("inicio");
    }]);



Route::group(array('before' => 'auth'), function()
{
    Route::any('marcaciones', [
        "as"=>"marcaciones",
        "uses"=>"MarcacionesController@index"
    ]);
});




App::missing(function($exception){
    return Response::view('error.error404',[],404);
});

//Route::get('/dominio', 'LoginController@prueba');

/*Route::get('/', 'HomeController@mostrarIndex');
Route::get('/contacto', 'HomeController@mostrarContacto');





Route::any('/',array('as'=>'index','uses'=>'HomeController@mostrarIndex'));
Route::any('/contacto',array('as'=>'contacto','uses'=>'HomeController@mostrarContacto'));*/


/*Redirecion a error 404*/
/*App::missing(function($exception){
  return Response::view('error.error404', array(), 404);
});*/



