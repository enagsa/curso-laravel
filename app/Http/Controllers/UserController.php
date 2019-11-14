<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request
;use Illuminate\Validation\Rule;
use App\Models\{User,Profession,Skill};
use App\Http\Requests\CreateUserRequest;

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
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');
        return view('user.create', compact('professions','skills','roles'));
    }

    public function edit(User $user){
    	$professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');
        return view('user.edit', compact('user','professions','skills','roles'));
    }

    public function store(CreateUserRequest $request){
        $request->createUser();

        return redirect()->route('users');
    }

    public function update(User $user){
        $data = request()->validate([
            'name' => 'required',
            'email' => [
                'required', 
                'email', 
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => ''
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El email insertado no es válido',
            'email.unique' => 'Email en uso',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password es demasiado corto'
        ]);

        if($data['password'] != null)
            $data['password'] = bcrypt($data['password']);
        else
            unset($data['password']);

        $user->update($data);

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user){
        $user->delete();

        return redirect()->route('users');
    }
}
