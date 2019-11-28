<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public static function findByEmail($email){
        return static::where(compact('email'))->first();
    }

    public function isAdmin(){
        return $this->role == 'admin';
    }

    public function profile(){
        return $this->hasOne(UserProfile::class)->withDefault();
    }

    public function skills(){
        return $this->belongsToMany(Skill::class, 'user_skill');
    }
}
