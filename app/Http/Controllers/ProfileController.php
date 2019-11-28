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
    	
    	$user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

    	$user->profile->update([
            'bio' => $request->bio,
            'twitter' => $request->twitter,
            'profession_id' => $request->profession_id
        ]);

    	return back();
    }
}
