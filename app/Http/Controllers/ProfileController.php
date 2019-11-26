<?php

namespace App\Http\Controllers;

use App\Models\{User,Profession};
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(){
    	$user = User::first(); 		// Normalmente auth()->user()

    	return view('profile.edit', [
    		'user' => $user,
    		'professions' => Profession::orderBy('title')->get()
    	]);
    }

    public function update(Request $request){
    	$user = User::first();  	// Normalmente auth()->user()

    	$data = $request->all();	// ADD VALIDATION!

    	if(empty($data['password']))
    		unset($data['password']);
    	else
    		$data['password'] = bcrypt($data['password']);
    	
    	$user->update($data);

    	$user->profile->update($data);

    	return back();
    }
}
