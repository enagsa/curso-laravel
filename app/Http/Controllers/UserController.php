<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Profession,Skill};
use App\Http\Requests\{CreateUserRequest,UpdateUserRequest};

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
        $user = new User;        
        return view('user.create', compact('user'));
    }

    public function edit(User $user){
        return view('user.edit', compact('user'));
    }

    public function store(CreateUserRequest $request){
        $request->createUser();

        return redirect()->route('users');
    }

    public function update(UpdateUserRequest $request, User $user){
        $request->updateUser($user);

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user){
        $user->delete();

        return redirect()->route('users');
    }
}
