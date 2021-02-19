<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Ruta pasado por un controlador que te devuelve una plantilla blade
a través de la función index de ese controller*/
//La función name indica el nombre con el que podemos referenciar en las plantillas {{ route('') }}
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

//Ruta simple pones la extension despues del get y te lleva a la plantilla blade
Route::get('/', function () {
    return view('index');
});
