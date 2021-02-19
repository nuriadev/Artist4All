@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-6/12 bg-white p-6 rounded-lg">
            <form action="{{ route('register') }}" method="POST">
                <!--todos los forms requieren de csrf-->
                <!--crea un token de protección para evitar peticiones del exterior-->
                @csrf
                <div class="mb-4">
                    <label for="name" class="sr-only">Nombre</label>
                    <input type="text" name="name" id="name" placeholder="Introduce tu nombre"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror" value="">

                    @error('name')
                        <div class="text-red-500 mt-2 text-sm">
                            Se requiere un nombre
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="username" class="sr-only">Nombre de usuario</label>
                    <input type="text" name="username" id="username" placeholder="Introduce tu nombre de usuario"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('username') border-red-500 @enderror" value="">

                    @error('username')
                    <div class="text-red-500 mt-2 text-sm">
                        Se requiere un nombre de usuario
                    </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="surname1" class="sr-only">1r Apellido</label>
                    <input type="text" name="surname1" id="surname1" placeholder="Introduce tu 1r apellido"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('surname1') border-red-500 @enderror" value="">

                    @error('surname1')
                    <div class="text-red-500 mt-2 text-sm">
                        Se requiere tu primer apellido
                    </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="surname1" class="sr-only">2º Apellido</label>
                    <input type="text" name="surname1" id="surname1" placeholder="Introduce tu 2º apellido"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('surname2') border-red-500 @enderror" value="">

                    @error('surname1')
                    <div class="text-red-500 mt-2 text-sm">
                        Se requiere tu segundo apellido
                    </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="sr-only">Email</label>
                    <input type="text" name="email" id="email" placeholder="Introduce tu email"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('email') border-red-500 @enderror" value="">

                    @error('email')
                    <div class="text-red-500 mt-2 text-sm">
                        Se requiere un email
                    </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="sr-only">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Introduce tu contraseña"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('password') border-red-500 @enderror" value="">

                    @error('password')
                    <div class="text-red-500 mt-2 text-sm">
                        Se requiere una contraseña
                    </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <!--

                    POR TERMINAR DE DOCUMENTAR

                    -->
                    <!-- el name password_confirmation nos servirá para verificar si las 2 contraseñas són iguales-->
                    <label for="password_confirmation" class="sr-only">Verificación de contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Introduce otra vez tu contraseña"
                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('password_confirmation') border-red-500 @enderror" value="">

                    @error('password_confirmation')
                    <div class="text-red-500 mt-2 text-sm">
                         Las contraseñas no coinciden
                    </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
