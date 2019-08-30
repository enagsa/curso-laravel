<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::all();     

        $title = 'Listado de usuarios';

    	return view('user.index', compact('title', 'users'));
    }

    public function show(User $user){
        return view('user.show', compact('user'));
    }

    public function create(){
        return view('user.create');
    }

    public function edit($id){
    	return view('user.edit', compact('id'));
    }

    public function store(){
        return 'Procesando información...';
    }
}
