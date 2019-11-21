<?php

namespace App\Http\ViewComponents;

use App\Models\{User,Profession,Skill};
use Illuminate\Contracts\Support\Htmlable;

class UserFields implements Htmlable{
	private $user;

	public function __construct(User $user){
		$this->user = $user;
	}

	public function toHtml(){
		return view('user._fields', [
        		'professions' => Profession::orderBy('title', 'ASC')->get(),
        		'skills' => Skill::orderBy('name', 'ASC')->get(),
        		'roles' => trans('users.roles'),
        		'user' => $this->user
        	])
			->render();
	}
}