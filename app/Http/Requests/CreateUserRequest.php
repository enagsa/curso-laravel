<?php

namespace App\Http\Requests;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | min:6',
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
                'nullable',
                Rule::in(Role::getList())
            ]
        ];
    }

    public function messages(){
        return [
            'first_name.required' => 'El campo nombre es obligatorio',
            'last_name.required' => 'El campo apellido es obligatorio',
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
            'role.in' => 'El campo rol debe ser válido'
        ];
    }

    public function createUser(){
        DB::transaction(function(){
            $user = User::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' =>  $this->role ?? 'user'
            ]);

            $user->profile()->create([
                'bio' => $this->bio,
                'twitter' => $this->twitter ?? null,                
                'profession_id' => $this->profession_id ?? null
            ]);

            $user->skills()->attach($this->skills ?? []);
        });
    }
}
