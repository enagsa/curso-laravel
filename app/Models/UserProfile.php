<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model,SoftDeletes};

class UserProfile extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function profession(){
        return $this->belongsTo(Profession::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
