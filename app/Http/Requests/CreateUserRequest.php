<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
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
            'name' => 'required',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | min:6',
            'bio' => 'required',
            'twitter' => 'nullable | url'
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
            'twitter.url' => 'La url de twitter insertada no es válida'
        ];
    }

    public function createUser(){
        DB::transaction(function(){
            $data = $this->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            $user->profile()->create([
                'bio' => $data['bio'],
                'twitter' => $data['twitter']
            ]);
        });
    }
}
