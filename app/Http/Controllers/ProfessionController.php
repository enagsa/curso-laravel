<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index(){
    	return view('profession.index', [
    		'professions' => Profession::orderBy('title')->get()
    	]);
    }

    public function destroy(Profession $profession){
    	$profession->delete();
    	return redirect(route('profession.index'));
    }
}
