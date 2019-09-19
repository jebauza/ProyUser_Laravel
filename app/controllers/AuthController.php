<?php

class AuthController extends BaseController {


    public function showLogin()
    {
        // Verificamos que el usuario no esté autenticado
        if (Auth::operador()->check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('/');
            //echo "Principal";die;
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return View::make('login');

        //echo "Vista login";die;
    }

    public function postLogin()
    {
        // Guardamos en un arreglo los datos del usuario.
        $userdata = array(
            'usuario' => Input::get('username'),
            'password'=> Input::get('password')
        );
        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        //echo Hash::make('qwe1234');die;
        /*if(Auth::user()->attempt($userdata, Input::get('remember-me', 0)))
        {
            // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/');
        }*/
        if(Auth::operador()->attempt($userdata,false))
        {
            // De ser datos válidos nos mandara a la bienvenida
            return Redirect::to('/');
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        return Redirect::to('login')
            ->with('mensaje_error', 'Tus datos son incorrectos')
            ->withInput();
    }

    public function logOut()
    {
        Auth::operador()->logout();
        return Redirect::to('login')
            ->with('mensaje_error', 'Tu sesión ha sido cerrada.');
    }


}
