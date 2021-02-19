<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //muestra la plantilla blade localizada en auth llamada register
    public function index() {
        return view('auth.register');
    }
    //inserta un usuario en la tabla de usuarios
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'surname1' => 'required|max:255',
            'surname2' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|max:255',
            /*confirmed mirará si hay algún dato del formulario con name
            parecido a "confirmed" y que coincida con el de este apartado*/
            'password' => 'required|confirmed',
        ]);
        //
        //hacer el insert
        dd('store');

    }
}
