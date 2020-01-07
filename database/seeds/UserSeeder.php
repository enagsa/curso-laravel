<?php

use App\Models\{User,Profession,UserProfile,Skill,Team};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
        $professions = Profession::all();
        $teams = Team::all();

        $user = factory(User::class)->create([
            'name' => 'Enrique Aguilar',
            'email' => 'enriqueaguilar@expacioweb.com',
            'password' => bcrypt('laravel'),
            'role' => 'admin',
            'team_id' => $teams->firstWhere('name', 'ExpacioWeb')->id,
            'created_at' => Carbon::now()->addDays(1)
        ]);

        $user->profile()->create([
            'bio' => 'Full-stack developer',
            'profession_id' => $professions->firstWhere('title', 'Desarrollador back-end')->id
        ]);

        $user->skills()->sync($skills);

        for($i=0;$i<999;$i++){
            $user = factory(User::class)->create([
                'team_id' => rand(0,2) ? null : $teams->random()->id  
            ]);

            factory(UserProfile::class)->create([
                'user_id' => $user->id,
                'profession_id' => rand(0,2) ? $professions->random()->id : null,
            ]);

            $user->skills()->sync($skills->random(rand(0,7)));
        }
    }
}
