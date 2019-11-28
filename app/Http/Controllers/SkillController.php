<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(){
    	return view('skill.index', [
    		'skills' => Skill::orderBy('name')->get()
    	]);
    }
}
