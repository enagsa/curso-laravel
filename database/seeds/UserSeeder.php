<?php

use App\Models\User;
use App\Models\Profession;
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
    	
        User::create([
        	'name' => 'Enrique Aguilar',
        	'email' => 'enriqueaguilar@expacioweb.com',
        	'password' => bcrypt('laravel'),
        	'profession_id' => $professionId,
            'is_admin' => true
        ]);

        User::create([
            'name' => 'Manu Paez',
            'email' => 'manuelpaez@expacioweb.com',
            'password' => bcrypt('laravel'),
            'profession_id' => $professionId
        ]);

        User::create([
            'name' => 'Jesús Melero',
            'email' => 'jesusmelero@expacioweb.com',
            'password' => bcrypt('laravel'),
            'profession_id' => null
        ]);
    }
}
