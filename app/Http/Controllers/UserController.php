<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
    	return 'Usuarios';
    }

    public function show($id){
    	return 'Mostrando detalle del usuario: '.$id; 
    }

    public function create(){
    	return 'Crear Nuevo Usuario';
    }

    public function edit($id){
    	return 'Editando detalles del usuario: '.$id;
    }
}
