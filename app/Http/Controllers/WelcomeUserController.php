<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function with($name, $nickname){
	    return 'Bienvenido '.ucfirst($name).', tu apodo es '.$nickname;
    }

    public function without($name){
    	return 'Bienvenido '.ucfirst($name);
    }
}
