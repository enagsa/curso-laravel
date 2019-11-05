<?php

use App\Models\{User,Profession,UserProfile};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$professionId = Profession::where('title', 'Desarrollador back-end')->value('id');

        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => bcrypt('laravel'),
            'is_admin' => true
        ]);

        $user->profile()->create([
            'bio' => 'Full-stack developer',
            'profession_id' => $professionId            
        ]);

        factory(User::class, 29)->create()->each(function($user){
            $user->profile()->create(
                factory(UserProfile::class)->raw()
            );
        });
    }
}
