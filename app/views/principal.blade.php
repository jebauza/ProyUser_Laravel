<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel PHP Framework</title>
</head>
<body>
    <div class="welcome">
        <h1>Bienvenido {{Auth::operadorer()->user()->nombre}}</h1>
        <a href="http://localhost/laravel4/public/logout">Cerrar sesión.</a>
    </div>
</body>
</html>