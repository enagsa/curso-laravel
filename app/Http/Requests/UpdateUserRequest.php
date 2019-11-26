<?php

namespace App\Http\Requests;

use App\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => 'nullable | min:6',
            'bio' => 'required',
            'twitter' => 'nullable | url',
            'profession_id' => [
                'nullable',
                Rule::exists('professions','id')->whereNull('deleted_at')
            ],
            'skills' => [
                'array',
                Rule::exists('skills', 'id')
            ],
            'role' => [
                'required',
                Rule::in(Role::getList())
            ]
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El email insertado no es válido',
            'email.unique' => 'Email en uso',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password es demasiado corto',
            'bio.required' => 'El campo biografia es obligatorio',
            'twitter.url' => 'La url de twitter insertada no es válida',
            'profession_id.exists' => 'El campo profesión es obligatorio',
            'skills.array' => 'El campo skills debe ser un array',
            'skills.exists' => 'El campo skills contiene valores no válidos',
            'role.required' => 'El campo nombre es obligatorio',
            'role.in' => 'El campo rol debe ser válido'
        ];
    }

    public function updateUser(User $user){
        $data = $this->validated();

        if(isset($data['password']) && $data['password'] != null)
            $data['password'] = bcrypt($data['password']);
        else
            unset($data['password']);

        $user->fill($data);
        $user->role = $data['role'];
        $user->save();

        $user->profile->update($data);

        $user->skills()->sync($data['skills'] ?? []);
    }
}