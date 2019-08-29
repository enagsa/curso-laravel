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
