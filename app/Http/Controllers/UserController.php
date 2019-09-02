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

    public function edit(User $user){
    	return view('user.edit', compact('user'));
    }

    public function store(){
        $data = request()->validate([
            'name' => 'required'
        ], [
            'name.required' => 'El campo nombre es obligatorio'
        ]);

        /*if(empty($data['name']))
            return redirect()->route('users.create')
                ->withErrors([
                    'name' => 'El campo nombre es obligatorio'
                ]);*/

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return redirect()->route('users');
    }
}
