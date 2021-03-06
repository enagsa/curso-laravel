<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios', 'UserController@index')
	->name('users');

Route::get('/usuarios/{user}', 'UserController@show')
	->where('user', '[0-9]+')
	->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')
	->name('users.create');

Route::post('/usuarios/crear', 'UserController@store')
	->name('users.store');

Route::get('/usuarios/{user}/edit', 'UserController@edit')
	->where('user', '[0-9]+')
	->name('users.edit');

Route::put('/usuarios/{user}', 'UserController@update')
	->where('user', '[0-9]+')
	->name('users.update');

Route::delete('/usuarios/{id}/borrar', 'UserController@destroy')
	->where('id', '[0-9]+')
	->name('users.destroy');

Route::patch('/usuarios/{id}/restaurar', 'UserController@restore')
	->where('id', '[0-9]+')
	->name('users.restore');

Route::patch('/usuarios/{user}/papelera', 'UserController@trash')
	->where('user', '[0-9]+')
	->name('users.trash');

Route::get('/usuarios/papelera', 'UserController@trashed')
	->name('users.trashed');

Route::get('/editar-perfil', 'ProfileController@edit')
	->name('edit.profile');

Route::put('/editar-perfil', 'ProfileController@update')
	->name('update.profile');

Route::get('/profesiones', 'ProfessionController@index')
	->name('profession.index');
Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')
	->where('profession', '[0-9]+')
	->name('profession.delete');

Route::get('/habilidades', 'SkillController@index')
	->name('skill.index');

Route::get('/saludo/{name}', 'WelcomeUserController@without');

Route::get('/saludo/{name}/{nickname}', 'WelcomeUserController@with');