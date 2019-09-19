<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />

        <title>ProyUser @yield('title')</title>


        @yield('head')
            {{ HTML::style('bootstrap/css/bootstrap.min.css'); }}
            {{ HTML::style('assets/principal/css/principal.css'); }}
        @yield('css')
        <link rel="icon" type="image/x-icon" href="{{ URL::to('images/UserProject-Logo.png')}}" />
    </head>


    <body>
        <header class="navbar navbar-inverse navbar-fixed-top " role="banner" >
            <div  class="container ">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{URL::route('inicio')}}">ProyUser</a>
                </div>
                <nav class="navbar-collapse ">
                    <ul class="nav navbar-nav">
                        <li @yield('activeInicio')><a href="{{URL::route('inicio')}}">Inicio</a></li>
                        <li @yield('activeMarcaciones')><a href="{{URL::route('marcaciones')}}">Reloj</a></li>
                        @if(false)
                        <li ><a href="">Directorio</a></li>
                        <li ><a href="">Almuerzos</a></li>
                        <li ><a href="">Documentación</a></li>
                        @endif
                    </ul>
                    <div class="nav navbar-nav navbar-right" >
                        <div  class=" btn-group" >
                        <?php
                        $foto_nombre = "mujer.jpg";
                        if(!empty(Auth::usuario()->get()->foto_nombre))
                        {
                            $foto_nombre = Auth::usuario()->get()->foto_nombre;
                        }
                        elseif(Auth::usuario()->get()->sexo == "M")
                        {
                            $foto_nombre = "hombre.jpg";
                        }
                        ?>
                            <a class="user"  href="#"><img src="{{ URL::to('images/trabajadores_CIDC/'.$foto_nombre)}}" class="img-circle">{{Auth::usuario()->get()->nombre_completo}}</a>
                            <a class=" user  dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @if(false)
                                <li><a href=""><span class="glyphicon glyphicon-user"></span>Mi perfil</a></li>
                                @endif
                                <li><a href="{{URL::route('logout')}}"><span class="glyphicon glyphicon-off"></span> Salir</a></li>

                            </ul>
                        </div>
                    </div>
                </nav>
                </div>
        </header>

        @yield('body')

    </body>
        {{ HTML::script('jquery/jquery-1.11.1.js'); }}
        {{ HTML::script('bootstrap/js/bootstrap.min.js'); }}
        @yield('js')
</html>







