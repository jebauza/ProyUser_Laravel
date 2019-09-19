@extends('layouts.bootstrap')

@section('head')
<title>Contacto</title>
<meta name="title" content="Página de Inicio">
<meta name="description" content="Página de Inicio">
<meta name="keywords" content="palabras, clave">
<meta name="robots" content="all">
@stop

@section('content')
<h1>Contacto</h1>
{{$mensage}}
{{Form::open(array(
                  'action'=>'HomeController@mostrarContacto',
                  'method'=>'post',
                  'rola'=>'form'
                  ))
}}

<div class="form-group">
{{Form::label('Nombre:')}}
{{Form::input('text','name',null,array('class'=>'form-control'))}}
</div>
<div class="form-group">
{{Form::label('Correo:')}}
{{Form::input('email','email',null,array('class'=>'form-control'))}}
</div>
<div class="form-group">
{{Form::label('Asunto:')}}
{{Form::input('text','asunto',null,array('class'=>'form-control'))}}
</div>
<div class="form-group">
{{Form::label('Mensaje:')}}
{{Form::textarea('msg',null,array('class'=>'form-control'))}}
</div>

{{Form::input('hidden','contacto')}}
{{Form::input('submit',null,'Enviar',array('class'=>'btn btn-primary'))}}
{{Form::close()}}
@stop