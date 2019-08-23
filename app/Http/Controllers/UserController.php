<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        if(request()->has('empty'))
            $users = [];
        else
            $users = [
                'User 1','User 2','User 3','User 4','User 5'
            ];

        $title = 'Listado de usuarios';

    	return view('user.index', compact('title', 'users'));
    }

    public function show($id){
        return view('user.show', compact('id'));
    }

    public function create(){
        return view('user.new');
    }

    public function edit($id){
    	return view('user.edit', compact('id'));
    }
}
