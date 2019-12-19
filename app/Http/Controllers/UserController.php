<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Profession,Skill};
use App\Http\Requests\{CreateUserRequest,UpdateUserRequest};

class UserController extends Controller
{
    public function index(){
        $users = User::orderBy('created_at','DESC')->paginate(); 
        $title = 'Listado de usuarios';
        $roles = trans('users.roles');
        $skills = Skill::orderBy('name', 'ASC')->get();

    	return view('user.index', compact('title', 'users', 'roles', 'skills'));
    }

    public function trashed(){
        $users = User::onlyTrashed()->paginate();    
        $title = 'Listado de usuarios en papelera';
        $roles = trans('users.roles');
        $skills = Skill::orderBy('name', 'ASC')->get();

        return view('user.index', compact('title', 'users', 'roles', 'skills'));
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

    public function trash(User $user){
        $user->delete();
        return redirect()->route('users');
    }

    public function destroy($id){
        $user = User::onlyTrashed()->whereId($id)->firstOrFail();
        $user->forceDelete();
        return redirect()->route('users.trashed');
    }

    public function restore($id){
        $user = User::onlyTrashed()->whereId($id)->firstOrFail();
        $user->restore();
        $user->profile()->restore();
        return redirect()->route('users');
    }
}
