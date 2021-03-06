<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function index(){
		$professions = Profession::query()
			->withCount('profiles')
			->orderBy('title')
			->get();

    	return view('profession.index', compact('professions'));
    }

    public function destroy(Profession $profession){
		abort_if($profession->profiles()->exists(), 400, 'Cannot delete a profession linked to a profile.');
    	$profession->delete();
    	return redirect(route('profession.index'));
    }
}
