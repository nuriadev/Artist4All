<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>Artist4all</title>
</head>
<body class="bg-gray-200">
    <!-- Plantilla base actual, de la que derivan las demás (esto pasará a ser angular)-->
    <nav class="p-6 bg-white flex justify-between mb-4">
        <ul class="flex items-center">
            <li>
                <a href="" class="p-3">Home</a>
            </li>
        </ul>
        <ul class="flex items-center">
            <li>
                <a href="" class="p-3">Login</a>
            </li>
            <li>
                <!--enlaza la ruta a la plantilla -->
                <a href="{{ route('register') }}" class="p-3">Registrar</a>
            </li>
            <li>
                <a href="" class="p-3">Logout</a>
            </li>
        </ul>
    </nav>
    <!-- yield permite insertar un trozo de código proviniente de otra plantilla blade-->
    @yield('content') <!--hace referencia los section('content') de los blade dependiendo de la ruta-->
</body>
</html>
