<?php

use App\Models\{User,Profession,UserProfile,Skill};
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
        $skills = Skill::all();

    	$professionId = Profession::where('title', 'Desarrollador back-end')->value('id');

        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => bcrypt('laravel'),
            'role' => 'admin'
        ]);

        $user->profile()->create([
            'bio' => 'Full-stack developer',
            'profession_id' => $professionId            
        ]);

        $user->skills()->sync($skills->random(6)->pluck('id'));

        factory(User::class, 29)->create()->each(function($user) use($skills){
            $user->profile()->create(
                factory(UserProfile::class)->raw()
            );
            $user->skills()->sync($skills->random(3)->pluck('id'));
        });
    }
}
