<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap core CSS -->
    {{ HTML::style('bootstrap/css/bootstrap.min.css'); }}
    {{ HTML::style('bootstrap/css/bootstrap.theme.min.css'); }}



  </head>

  <body>
    <div class="container" style='margin-top: 80px'>
     <h1><span class="glyphicon glyphicon-fire"></span>Error 404, la Pagina solicitada no existe</h1>
     <a href="{{URL::Route('inicio')}}">Regresar a la pagina de inicio</a>
    </div><!-- /.container -->
  </body>
  {{ HTML::script('jquery/jquery-1.js'); }}
  {{ HTML::script('bootstrap/js/bootstrap.min.js'); }}
</html>
