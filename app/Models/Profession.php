<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    public $timestamps = false;

    protected $fillable = ['title'];

    public function profiles(){
    	return $this->hasMany(UserProfile::class);
    }
}
