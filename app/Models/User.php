<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use SoftDeletes;
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

    public function team(){
        return $this->belongsTo(Team::class)->withDefault([
            'name' => '(Sin empresa)'
        ]);
    }

    public function delete(){
        $this->profile->delete();
        return parent::delete();
    }

    public function forceDelete(){
        $this->forceDeleting = true;

        $this->profile->forceDelete();
        $this->skills()->sync([]);

        return tap($this->delete(), function ($deleted) {
            $this->forceDeleting = false;

            if ($deleted) {
                $this->fireModelEvent('forceDeleted', false);
            } else {
            }
        });
    }

    public function scopeSearch($query, $search){
        if(empty($search)){
            return;
        }

        $query->where(function($query) use ($search){
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('team', function($query) use ($search){
                    $query->where('name', 'like', "%{$search}%");
                });
        });
    }
}
