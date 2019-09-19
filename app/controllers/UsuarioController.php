<?php



class UsuarioController extends BaseController {


    public function prueba()
    {
        include (app_path() . "/lib/Ldap/Ldap.php");


        $classLldap = new Ldap();
        //echo $classLldap->pru();die;
    }

    public function login()
    {
        if(Auth::check())
        {
            return View::make("layouts.principal");
        }
        else
        {
            if(Input::server("REQUEST_METHOD") == "POST")
            {
                $reglas = [
                    "user" => 'required|min:3|max:32',
                    "password" => "required|min:6"
                ];
                $campos = [
                    'user'=>Input::get("username"),
                    'password'=>Input::get("password")
                ];
                $mensajesError = [
                    'user.required' => "El campo Usuario es requerido",
                    'user.min' => "El minimo permitido son 3 caracteres",
                    'user.max' => "El maximo permitido son 32 caracteres",
                    'password.required' => "El campo Password es requerido",
                    'password.min' => "El minimo permitido son 6 caracteres"
                ];
                $validador = Validator::make($campos, $reglas,$mensajesError);
                if($validador->passes())
                {
                    //Esto es cuando compruebe que es valido en el Ldap
                    /*$usuario = Usuario::where('user', '=', $campos['user'])->first();
                    $usuario->password = Hash::make($campos['user']);
                    $usuario->save();*/


                    $conexion = DB::connection('mysql');
                    $usuarioSinDominio = Usuario::where('ci', '=', Input::get("username"))
                        ->where('ci', '=', Input::get("password"))
                        ->where('password', '=', "")
                        ->first();
                    //echo $usuarioSinDominio->id;die;
                    if($usuarioSinDominio)
                    {
                        Auth::usuario()->login($usuarioSinDominio);
                        return Redirect::route("inicio");
                    }
                    else
                    {
                        if(Auth::usuario()->attempt($campos))
                        {
                            return Redirect::route("inicio");
                        }
                    }
                    return Redirect::back()->withInput()->withErrors(['inValidos'=>"Usuario o contraseña incorectos"]);
                }
                return Redirect::back()->withInput()->withErrors($validador);
            }
            return View::make("login.login");
        }

    }












}
