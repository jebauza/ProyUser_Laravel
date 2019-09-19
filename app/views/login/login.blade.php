<!DOCTYPE html>
<html lang="en">
<head>
    <title>ProyUser</title>
    <meta charset="UTF-8" />
    {{ HTML::style('bootstrap/css/bootstrap.min.css'); }}
    {{ HTML::style('assets/login/css/login.css'); }}

    <link rel="icon" type="image/x-icon" href="{{ URL::to('images/UserProject-Logo.png')}}" />

</head>
<body>

<div class="logo-box">
<h2> <img  src="{{ URL::to('images/UserProject-Logo.png')}}" id="logo"  alt=""/>ProyUser</h2></div>
<div id="loginbox" class="">
   {{ Form::open(array('url' => URL::route("login"),'class'=>"form-vertical",'method'=>"post",'id'=>"loginform")) }}
        <div class=" panel">
            <div class="bg-danger" >{{$errors->first('inValidos')}}</div>
            <div class="input-group ">
                <span  class="input-group-addon "><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" id="_username" name="username" class="form-control" placeholder="Usuario" required="required" title="">
                <div class="bg-danger" >{{$errors->first('usuario')}}</div>
            </div>
            <div class="input-group ">
                <span class="input-group-addon"><i class=" glyphicon glyphicon-lock"></i></span>
                <input type="password" id="_password" name="password" class="form-control" placeholder="Clave" required="required" title="">
                <div class="bg-danger" >{{$errors->first('password')}}</div>
            </div>


            <div class="form-actions panel-footer">
                <span class="pull-right"><input type="submit" name="login" class="btn btn-primary btn-primary" value="Entrar" /></span>
            </div>
        </div>
   {{ Form::close() }}
</div>

</body>

    {{ HTML::script('bootstrap/js/bootstrap.min.js'); }}
    {{ HTML::script('jquery/jquery-1.js'); }}
    {{ HTML::script('assets/login/js/login.js'); }}

</html>