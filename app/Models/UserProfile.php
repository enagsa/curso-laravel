<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = ['bio', 'twitter', 'profession_id'];

    public function profession(){
        return $this->belongsTo(Profession::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
