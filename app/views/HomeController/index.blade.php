<h1>Vista Index</h1>
 <?php
 foreach($numeros as $value)
 {
   echo $value.'</br>';
 }
 ?>

 @foreach($numeros as $value)
 {{$value}}
 @endforeach

 {{-- */$variable=4;/* --}}

 @if($variable==5)
 {{$variable}} es igual a 5
 @else
 no es igual
 @endif